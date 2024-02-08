<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\P_clinic;
use App\Models\Especialidade;
use App\Models\User;
use App\Models\Role;
use DB;

class P_clinicController extends Controller
{
    //

    public function formCreateAccountP_clinic () {

        $dados = [
            'dias' => P_clinic::getDias(),
            'especialidades' => Especialidade::all(),
        ];

        return view('Admin.Cadastro.cadastroDoctor')->with($dados);
    }


    public function createAccountUserP_clinic (Request $request) {

        // dd($request);

        // $retorno = Validacao::validarDadosCriacaoDeConta($request);
        // if ($retorno['estado'] == true) {
        // } else {
        //    $retorno['estado'] = false;
        //    return response([
        //        'retorno' => $retorno
        //    ]);
        // }

        $retorno['estado'] = true;
        //dd($request);

        $allUsers = User::all();
        foreach ($allUsers as $user) {

            if($user->email == $request['email']){
                $retorno['jaExisteEmail'] = "email já está a ser utilizado!";
                $retorno['estado'] = false;
                break;
            };

            if($user->bi == $request['bi']){
                $retorno['jaExistebi'] = "bilhete já está a ser utilizado!";
                $retorno['estado'] = false;
                break;
            };

        }

        if($retorno['estado'] == false) return response([
            'retorno' => $retorno,
        ]);

        try {
            DB::beginTransaction();

            // registrando o p_clinic como user
            $user = new User();
            $user->nome         = filter_var($request['nome'], FILTER_SANITIZE_STRING);
            $user->sobreNome    = filter_var($request['sobreNome'], FILTER_SANITIZE_STRING);
            $user->email        = filter_var($request['email'], FILTER_SANITIZE_STRING);
            $user->password     = bcrypt($request['password']);
            $user->bi           = filter_var($request['bi'], FILTER_SANITIZE_STRING);
            $user->tipo         = "pessoal clinico";
            $user->save();

            Role::storeRoleUser($user->id, 4);

            // registrando-o como paciente
            $pClinc = new P_clinic();
            $pClinc->estado= 0;
            $pClinc->CRM= filter_var($request['CRM'], FILTER_SANITIZE_STRING);
            $pClinc->especialidade= $request['especialidade'];
            $pClinc->user_id= $user->id;
            $pClinc->save();

            $dias = explode(',', $request['dias']);

            P_clinic::storeDiasTrabalho($pClinc->id, $dias);

            DB::commit();

            $retorno['estado'] = true;
            $retorno['dados_validos'] = [
                'numero_user' => $user->email,
                'password' => $request['password'],
            ];

        } catch (Exception $th) {
            DB::rollBack();

            DB::beginTransaction(false);

            try {
                addErro($th, 'Erro ao criar conta!');
            } catch (Exception $th) {
            }

            $retorno['error_sql'] = $th->getMessage();
            $retorno['estado'] = false;
        }

        $retorno['erros_validacao_user'] = [];
        $retorno['erros_validacao_paciente'] = [];
        $retorno['erros_validacao_rcp'] = [];
        
        return response([
            'retorno' => $retorno,
        ]);

    }
}
