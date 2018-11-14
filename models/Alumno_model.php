<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumno_model extends CI_Model {
    
    private $campus;
    
    public function __construct() {
        parent::__construct();
        $this->campus = $this->load->database('campus', TRUE);
    }

    public function listNuevos($plantel, $year, $periodo, $nivel, $modalidad, $carrera = false) {
        $sql = "SELECT ";
        $sql.= "P.TAX_ID AS matricula, ";
        $sql.= "A.PEOPLE_ID AS idpc, ";
        $sql.= "(P.FIRST_NAME+' '+P.MIDDLE_NAME) nombre, ";
        $sql.= "P.LAST_NAME ap1, ";
        $sql.= "P.Last_Name_Prefix ap2, ";
        
        $sql.= "(SELECT TOP 1 EMAIL_ADDRESS FROM ADDRESSSCHEDULE ";
        $sql.= "WHERE PEOPLE_ORG_ID = A.PEOPLE_ID ";
        $sql.= "AND ADDRESS_TYPE = 'DOM' ORDER BY START_DATE DESC) AS EMAIL_ADDRESS, ";
        
        $sql.= "(SELECT PersonPhone.PhoneNumber FROM PEOPLE ";
        $sql.= "LEFT JOIN PersonPhone ";
        $sql.= "ON PEOPLE.PrimaryPhoneId = PersonPhone.PersonPhoneId ";
        $sql.= "WHERE PEOPLE.PEOPLE_ID = A.PEOPLE_ID) AS PhoneNumber, ";
        
        $sql.= "C2.LONG_DESC AS carrera, ";
        $sql.= "C.CRG_CRD_DESC AS concepto, ";
        $sql.= "C.PAID_AMOUNT AS pago, ";
        $sql.= "C.REVISION_DATE AS fecha_pago, ";
        $sql.= "cd.MEDIUM_DESC AS desicion, cs.MEDIUM_DESC AS statuss ";
        
        $sql.= "FROM ACADEMIC AS A ";
        $sql.= "INNER JOIN CHARGECREDIT AS C ";
        $sql.= "ON A.PEOPLE_ID = C.PEOPLE_ORG_ID ";
        $sql.= "AND A.ACADEMIC_YEAR = C.ACADEMIC_YEAR ";
        $sql.= "AND A.ACADEMIC_TERM = C.ACADEMIC_TERM ";
        $sql.= "AND A.ACADEMIC_SESSION = C.ACADEMIC_SESSION ";
        $sql.= "AND A.APP_STATUS = 'INS' ";
        $sql.= "AND C.SUMMARY_TYPE IN ('INSC') ";
        $sql.= "AND C.VOID_FLAG = 'N' ";
        $sql.= "AND C.PAID_AMOUNT <> 0 ";

        $sql.= "INNER JOIN CODE_APPDECISION as cd ";
        $sql.= "ON A.APP_DECISION = cd.CODE_VALUE_KEY ";

        $sql.= "INNER JOIN CODE_APPSTATUS as cs ";
        $sql.= "ON A.APP_STATUS = cs.CODE_VALUE_KEY ";

        $sql.= "INNER JOIN CODE_CURRICULUM AS C2 ";
        $sql.= "ON A.CURRICULUM = C2.CODE_VALUE_KEY ";

        $sql.= "INNER JOIN PEOPLE AS P ";
        $sql.= "ON A.PEOPLE_ID = P.PEOPLE_ID ";

        $sql.= "WHERE A.ACADEMIC_YEAR ='$year' ";
        $sql.= "AND A.ACADEMIC_TERM = '$periodo' ";
        $sql.= "AND A.DEGREE = '$grado' ";
        $sql.= "AND A.PROGRAM ='$modalidad' ";
        $sql.= "AND A.ACADEMIC_SESSION = '$plantel' ";
        $sql.= "AND A.APP_STATUS IS NOT NULL ";
        $sql.= "AND A.APP_DECISION IS NOT NULL ";

        if ( $carrera ) {
            if( $carrera == 'PLAT' || $carrera == 'PLAGT' ) {
                $sql.= "AND A.CURRICULUM IN ('PLAT','PLAGT') ";
            } else if ( $carrera == 'PLEC' || $carrera == 'PLEF' ) {
                $sql.= "AND A.CURRICULUM IN ('PLEC','PLEF') ";
            } else {
                $sql.= "AND A.CURRICULUM IN ('$carrera') ";
            }
        }

        $sql.= "ORDER BY A.PEOPLE_ID ";

        $query = $this->campus->query($sql);

        return $query->result();
    }

    public function listReinscritos($plantel, $year, $periodo, $nivel, $modalidad, $carrera = false) {
        $last_year = $year - 1;
        $periodo_anterior = '';
        switch ( $periodo ) {
            case 'SEMESTREA': {
                $periodo_anterior = 'SEMESTREB';
                break;
            }
            case 'SEMESTREB': {
                $periodo_anterior = 'SEMESTREA';
                break;
            }
            case 'CUATRIMESA': {
                $periodo_anterior = 'CUATRIMESC';
                break;
            }
            case 'CUATRIMESB': {
                $periodo_anterior = 'CUATRIMESA';
                break;
            }
            case 'CUATRIMESC': {
                $periodo_anterior = 'CUATRIMESB';
                break;
            }
        }

        $sql = "SELECT ";
        $sql.= "A.APP_STATUS, ";
        $sql.= "A.APP_DECISION, ";
        $sql.= "C.AMOUNT, ";
        $sql.= "C.PAID_AMOUNT, ";
        $sql.= "P.TAX_ID matricula, ";
        $sql.= "A.PEOPLE_ID idpc, ";
        $sql.= "(P.FIRST_NAME+' '+P.MIDDLE_NAME) nombre, ";
        $sql.= "P.LAST_NAME ap1, ";
        $sql.= "P.Last_Name_Prefix ap2, ";
        
        $sql.= "(SELECT TOP 1 EMAIL_ADDRESS FROM ADDRESSSCHEDULE ";
        $sql.= "WHERE PEOPLE_ORG_ID = A.PEOPLE_ID ";
        $sql.= "AND ADDRESS_TYPE = 'DOM' ORDER BY START_DATE DESC) AS EMAIL_ADDRESS, ";
        
        $sql.= "(SELECT PersonPhone.PhoneNumber FROM PEOPLE ";
        $sql.= "LEFT JOIN PersonPhone ";
        $sql.= "ON PEOPLE.PrimaryPhoneId = PersonPhone.PersonPhoneId ";
        $sql.= "WHERE PEOPLE.PEOPLE_ID = A.PEOPLE_ID) AS PhoneNumber, ";
        
        $sql.= "A.CURRICULUM clave_carrera, ";
        $sql.= "CODE_XDESC carrera, ";
        
        $sql.= "CASE A2.CURRICULUM ";
        $sql.= "WHEN 'PLAT' THEN 'PLAGT' ";
        $sql.= "WHEN 'PLEC' THEN 'PLEF' ";
        $sql.= "WHEN 'PLCR' THEN 'PLCM' ";
        $sql.= "ELSE A2.CURRICULUM ";
        $sql.= "END as car2, ";
        
        $sql.= "A2.ACADEMIC_SESSION plantel2, ";
        $sql.= "A2.PROGRAM programa2, ";
        $sql.= "A2.DEGREE nivel2, ";
        $sql.= "A2.ENROLL_SEPARATION estatus2, ";
        $sql.= "C.CHARGECREDITNUMBER,  ";
        $sql.= "C.CHARGE_CREDIT_CODE clave_concepto, ";
        $sql.= "C.CRG_CRD_DESC concepto, ";
        $sql.= "C.PAID_AMOUNT pago, ";
        
        $sql.= "(SELECT TOP 1 c6.ENTRY_DATE ";
        $sql.= "FROM CHARGECREDIT c3 ";
        $sql.= "INNER JOIN ChargeCreditApplication c4 ";
        $sql.= "ON c3.CHARGECREDITNUMBER = c4.ChargeAppliedTo ";
        $sql.= "INNER JOIN CHARGECREDIT c5 ";
        $sql.= "ON c4.ChargeCreditSource = c5.CHARGECREDITNUMBER AND c5.VOID_FLAG = 'N' ";
        $sql.= "INNER JOIN CASHRECEIPT c6 ";
        $sql.= "ON c5.RECEIPT_NUMBER = c6.RECEIPT_NUMBER AND c6.VOID_FLAG = 'N' ";
        $sql.= "WHERE c3.CHARGECREDITNUMBER = C.CHARGECREDITNUMBER ORDER BY c6.ENTRY_DATE DESC) fecha_pago, ";
        
        $sql.= "(SELECT COUNT(t.EVENT_ID) total ";
        $sql.= "FROM TRANSCRIPTDETAIL as t ";
        $sql.= "WHERE t.PEOPLE_ID = A.PEOPLE_ID ";
        $sql.= "AND EVENT_ID = '000000') as mat0, ";
        
        $sql.= "(SELECT TOP 1 ";
        $sql.= "(a3.ACADEMIC_YEAR+'-'+a3.ACADEMIC_TERM+'-'+a3.PROGRAM+'-'+a3.DEGREE+'-'+a3.CURRICULUM ";
        $sql.= "+'-'+a3.ENROLL_SEPARATION+'-'+a3.ACADEMIC_SESSION) ";
        $sql.= "FROM ACADEMIC a3 ";
        $sql.= "WHERE a3.PEOPLE_ID = A.PEOPLE_ID ";
        $sql.= "AND a3.ACADEMIC_YEAR <= ".$last_year." ";
        $sql.= "AND a3.ACADEMIC_TERM <> '' ";
        $sql.= "AND a3.ACADEMIC_SESSION <> '' ";
        $sql.= "AND a3.MATRIC = 'Y' ";
        $sql.= "AND a3.ACADEMIC_FLAG = 'Y' ";
        $sql.= "ORDER BY a3.ACADEMIC_YEAR DESC, a3.ACADEMIC_TERM DESC) ac_ant, ";
        
        $sql.= "(SELECT TOP 1 ";
        $sql.= "(t2.[SECTION]+'-'+t2.ACADEMIC_YEAR+'-'+t2.ACADEMIC_TERM+'-'+t2.ACADEMIC_SESSION) ";
        $sql.= "FROM TRANSCRIPTDETAIL as t2 ";
        $sql.= "WHERE t2.PEOPLE_ID = A.PEOPLE_ID ";
        $sql.= "AND t2.EVENT_ID != '000000' ";
        $sql.= "AND t2.ACADEMIC_SESSION <> '' ";
        $sql.= "AND t2.ACADEMIC_TERM <> '' ";
        $sql.= "AND t2.ACADEMIC_YEAR <= ".$year." ";
        $sql.= "AND t2.[SECTION] NOT LIKE 'E%' ";
        $sql.= "AND t2.[SECTION] NOT LIKE 'I%' ";
        $sql.= "AND t2.[SECTION] NOT LIKE 'C%' ";
        $sql.= "AND t2.[SECTION] NOT LIKE '%OT%' ";
        $sql.= "AND t2.[SECTION] NOT LIKE 'T%' ";
        $sql.= "ORDER BY t2.[SECTION] DESC, t2.ACADEMIC_TERM DESC) grupo ";
        $sql.= "FROM ACADEMIC A ";
        $sql.= "LEFT JOIN CHARGECREDIT C ";
        $sql.= "ON A.PEOPLE_ID = C.PEOPLE_ORG_ID ";
        $sql.= "AND A.ACADEMIC_YEAR = C.ACADEMIC_YEAR ";
        $sql.= "AND A.ACADEMIC_TERM = C.ACADEMIC_TERM ";
        $sql.= "AND A.ACADEMIC_SESSION = C.ACADEMIC_SESSION ";
        
        /*
        * Verificar que hace esta query, $colegiaturaD2 nunca es definido en alumnos_reinscritos_detalle.php

        if ( $grado == 'MTRIA' ) {
            $sql.= "AND ( SUMMARY_TYPE IN ('INSC') OR CHARGE_CREDIT_CODE IN ('$colegiaturaM') )";
        }
        else if ( $grado == 'DOCT' ) {
            $sql.= "AND ( SUMMARY_TYPE IN ('INSC') ";
            $sql.="OR CHARGE_CREDIT_CODE IN ('$colegiaturaD1','$colegiaturaD2') )";
        }
        else{
            $sql.= "AND SUMMARY_TYPE IN ('INSC')";
        }
        */
        $sql.= "AND VOID_FLAG = 'N' ";
        $sql.= "AND PAID_AMOUNT <> 0 ";
        
        $sql.= "INNER JOIN PEOPLE P ";
        $sql.= "ON A.PEOPLE_ID = P.PEOPLE_ID ";

        $sql.= "LEFT JOIN CODE_CURRICULUM C2 ";
        $sql.= "ON A.CURRICULUM = C2.CODE_VALUE_KEY ";
        $sql.= "LEFT JOIN ACADEMIC A2 ";
        $sql.= "ON A.PEOPLE_ID = A2.PEOPLE_ID ";
        $sql.= "AND A2.ACADEMIC_YEAR = '".$last_year."' ";
        $sql.= "AND A2.ACADEMIC_TERM = '".$periodo_anterior."' ";
        $sql.= "AND A2.ACADEMIC_SESSION <> '' ";
        $sql.= "AND A2.ACADEMIC_FLAG = 'Y' ";
        $sql.= "AND A2.MATRIC = 'Y' ";
        $sql.= "AND A2.PRIMARY_FLAG = 'Y' ";
        
        $sql.= "WHERE A.ACADEMIC_YEAR = '".$year."' ";
        $sql.= "AND A.ACADEMIC_TERM = '".$periodo."' ";
        $sql.= "AND A.ACADEMIC_SESSION = '".$plantel."' ";
        $sql.= "AND A.DEGREE = '".$grado."' ";
        $sql.= "AND A.PROGRAM = '".$modalidad."' ";
        
        if ( $carrera ) {
            if( $carrera == 'PLAT' || $carrera == 'PLAGT' ) {
                $sql.= "AND A.CURRICULUM IN ('PLAT','PLAGT') ";
            } else if ( $carrera == 'PLEC' || $carrera == 'PLEF' ) {
                $sql.= "AND A.CURRICULUM IN ('PLEC','PLEF') ";
            } else {
                $sql.= "AND A.CURRICULUM IN ('$carrera') ";
            }
        }

        $sql.= "AND A.ENROLL_SEPARATION NOT IN ('BAJA', 'BACE', 'BATE', 'BAFI') ";
        $sql.= "AND A.ACADEMIC_FLAG = 'Y' ";
        $sql.= "AND A.PRIMARY_FLAG = 'Y' ";
        $sql.= "AND A.MATRIC = 'Y' ";
        $sql.= "AND (C.PAID_AMOUNT - C.AMOUNT = 0) ";
        $sql.= "ORDER BY CODE_XDESC, A.PEOPLE_ID";

        $query = $this->campus->query($sql);

        return $query->result();
    }

    public function listBajas($plantel, $year, $periodo, $nivel, $modalidad, $carrera = false) {
        $sql = "SELECT DISTINCT ";
        $sql.= "TAX_ID, ";
        $sql.= "PEOPLE_ID AS id, ";
        $sql.= "(nombre+' '+ nombreuno+' '+ apellido) nombre, ";
        $sql.= "LONG_DESC baja, ";
        $sql.= "CURRICULUM, ";
        $sql.= "CODE_XDESC, ";
        $sql.= "grupo, ";
        $sql.= "Convert(NVARCHAR,fecha,103) fecha_baja ";

        $sql.= "FROM ";
        $sql.= "( SELECT ";
        $sql.= "ac.PEOPLE_ID, ";
        $sql.= "pe.FIRST_NAME as nombre, ";
        $sql.= "pe.MIDDLE_NAME as nombreuno, ";
        $sql.= "pe.LAST_NAME as apellido, ";
        $sql.= "pe.TAX_ID, ";
        $sql.= "ac.CURRICULUM, ";
        $sql.= "ac.MATRIC_YEAR, ";
        $sql.= "UPPER(td.EVENT_ID) as EVENT_ID, ";
        $sql.= "td.[SECTION] grupo, ";
        $sql.= "sp.PERSON_CODE_ID, ";
        $sql.= "po.FIRST_NAME, ";
        $sql.= "po.MIDDLE_NAME, ";
        $sql.= "po.LAST_NAME, ";
        $sql.= "ac.PROGRAM, ";
        $sql.= "ac.DEGREE, ";
        $sql.= "ac.ACADEMIC_YEAR, ";
        $sql.= "ac.ACADEMIC_TERM, ";
        $sql.= "ac.ACADEMIC_SESSION, ";
        $sql.= "ac.ENROLL_SEPARATION, ";
        $sql.= "ce.LONG_DESC, ";
        $sql.= "cu.CODE_XDESC, ";
        //$sql.= "ac.ENROLLMENT_STATUS_DATE fecha ";
        $sql.= "ac.REVISION_DATE fecha ";
        
        $sql.= "FROM dbo.ACADEMIC AS ac ";
        $sql.= "INNER JOIN dbo.PEOPLE AS pe ";
        $sql.= "ON ac.PEOPLE_ID = pe.PEOPLE_ID ";
        $sql.= "INNER JOIN dbo.TRANSCRIPTDETAIL AS td ";
        $sql.= "ON ac.ACADEMIC_YEAR = td.ACADEMIC_YEAR ";
        $sql.= "AND ac.ACADEMIC_TERM = td.ACADEMIC_TERM ";
        $sql.= "AND ac.ACADEMIC_SESSION = td.ACADEMIC_SESSION ";
        $sql.= "AND ac.PEOPLE_CODE_ID = td.PEOPLE_CODE_ID ";
        $sql.= "LEFT JOIN dbo.SECTIONPER AS sp ";
        $sql.= "ON td.ACADEMIC_YEAR = sp.ACADEMIC_YEAR ";
        $sql.= "AND td.ACADEMIC_TERM = sp.ACADEMIC_TERM ";
        $sql.= "AND td.ACADEMIC_SESSION = sp.ACADEMIC_SESSION ";
        $sql.= "AND td.EVENT_ID = sp.EVENT_ID ";
        $sql.= "AND td.[SECTION] = sp.[SECTION] ";
        $sql.= "INNER JOIN dbo.PEOPLE AS po ";
        $sql.= "ON sp.PERSON_CODE_ID = po.PEOPLE_CODE_ID ";
        $sql.= "INNER JOIN dbo.CODE_ENROLLMENT AS ce ";
        $sql.= "ON ac.ENROLL_SEPARATION = ce.CODE_VALUE_KEY ";
        $sql.= "LEFT JOIN dbo.CODE_CURRICULUM AS cu ";
        $sql.= "ON ac.CURRICULUM = cu.CODE_VALUE_KEY ";

        $sql.= "WHERE ac.ACADEMIC_SESSION = '$plantel' ";
        $sql.= "AND ac.ACADEMIC_YEAR = '$year' ";
        $sql.= "AND ac.ACADEMIC_TERM = '$periodo' ";
        $sql.= "AND ac.PROGRAM IN ('$modalidad') ";

        if ( $nivel ) {
            $sql.= "AND ac.DEGREE IN ('".$nivel."') ";
        }
        if ( $carrera ) {
            if( $carrera == 'PLAT' || $carrera == 'PLAGT' ) {
                $sql.= "AND A.CURRICULUM IN ('PLAT','PLAGT') ";
            } else if ( $carrera == 'PLEC' || $carrera == 'PLEF' ) {
                $sql.= "AND A.CURRICULUM IN ('PLEC','PLEF') ";
            } else {
                $sql.= "AND A.CURRICULUM IN ('$carrera') ";
            }
        }

        if( $carrera != 'PLLEX' || $carrera != 'TLLEX' ){
            $sql.= "AND td.EVENT_ID NOT LIKE 'LE%' ";
        }
        

        $sql.= "AND ac.ENROLL_SEPARATION IN ('BAJA','BATE','BAFI','BACE') ";
        $sql.= "AND ac.ACADEMIC_FLAG = 'y' ";
        //$sql.= "--AND td.EVENT_ID <> '000000' ";
        $sql.= "AND td.ADD_DROP_WAIT <> 'D' ";
        $sql.= "$SinGruposIngles ) tabla ";
        $sql.= "ORDER BY nombre ";

        $query = $this->campus->query($sql);

        return $query->result();
    }

}