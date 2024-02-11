<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class P_clinic extends Model
{
    use HasFactory;



    public static function getDias () {
        return DB::select("SELECT * FROM dias");
    }

    public static function storeDiasTrabalho ($p_clinic_id, $dias) {

        $queryImpr = "INSERT INTO dias_trabalho(p_clinic_id, dia_id) VALUES ";

        foreach ($dias as $dia) {
            # code...
            $queryImpr = $queryImpr."($p_clinic_id,$dia),";
        }

        $query = rtrim($queryImpr, ",");

        DB::insert($query);
    }
    
    public static function getAllDoctores () {
        return DB::select("SELECT u.nome, u.sobrenome, u.foto, e.nome as nome_especialidade 
                        FROM p_clinics p 
                        INNER JOIN users u ON (p.user_id=u.id) 
                        INNER JOIN especialidades e ON (p.especialidade=e.id) 
                        WHERE p.especialidade <> -1"); 
    }

    public static function getP_clinic () {
        return DB::select("SELECT u.nome, u.sobreNome, p.id FROM users u INNER JOIN p_clinics p ON (u.id=p.user_id) WHERE u.tipo = 'pessoal clinico'");
    }



}
