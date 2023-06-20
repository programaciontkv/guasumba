<?php
include_once("../Clases/clsMaquinas.php");
include_once("../Clases/clsReportes.php");
include_once("../Clases/clsPedido.php");
include_once("../Clases/clsPoductos.php");
include_once("../Clases/clsSecciones.php");
include_once '../Clases/clsMateriaPrima.php';
include_once("../Clases/clsRegistroExtrusion.php");
include_once("../Clases/clsRegistroImpresion.php");
include_once("../Clases/clsRegistroSellado.php");
require_once 'fpdf.php';
date_default_timezone_set('America/Guayaquil');
set_time_limit(0);
$from=$_POST['f_month_from'];
$until=$_POST['f_month_until'];
$Maq=new Maquinas();
class PDF extends FPDF
{
function resumen($from,$until)
{
        $Sec=new Secciones();
        $Mp=new MateriaPrima();
        $cnsSec=$Sec->listaSeccionesPolietileno();
        $cnsSec0=$Sec->listaSeccionesPolietileno();
        $gt=pg_fetch_array($Mp->listaSumaTotalIMPbyDate($from,$until));
        $w=array(40,12);
        $this->SetFont('Arial','i',8);
        $this->SetFont('Arial','B',14);
        $this->SetFillColor(240,240,240);
        $this->Ln();
        $this->Cell(0,3,'RESUMEN GENERAL OPERATIVO POR SECCION',0,1,'C');
        $this->Ln(2);
        $this->SetFont('Arial','',11);
        $this->Cell($w[0],7,'Desde: '.$from,0,0);
        $this->Cell($w[0],7,'Hasta: '.$until,0,0);
        $this->Cell($w[0],7,'',0,0);
        $this->Cell($w[0]*3,7,'Tambillo / Polietileno',0,0,'R');
        $this->Ln(6);
        $this->SetFont('Arial','B',8);
        $h=4.2; 
        $cnt=0;
        $cn=0;
            $this->Cell($w[0]*7,$h,'CONSUMO DE MATERIALES',1,0,'C',true);$this->Ln();
            $this->Cell($w[0],$h*2,'MATERIA PRIMA',1,0,'C',true);
            while ($rstSec=pg_fetch_array($cnsSec)){$cnt=$cnt+2;$this->Cell($w[1]*2,$h,$rstSec[sec_nombre],1,0,'C',true);}
            $this->Cell($w[1]*2,$h,'TOTAL',1,0,'C',true);
            $this->Cell(0.4,$h,'',1,0,'C',true);
            $this->Ln();
            $this->Cell($w[0],$h*3,'',0,0,'C');   
            $cn=$cnt;
            while(($cnt+2)>0){
                   $this->Cell($w[1],$h,'kg',1,0,'C',true);
                   $this->Cell($w[1],$h,'%',1,0,'C',true);
                $cnt=$cnt-2;
                }
            $this->Ln();
//*****Datos***********
$this->SetFont('Arial','',7);
$cnsMp=$Mp->listaReporteMP0_noRepRec();
$mptp='';
    while($rstMp=pg_fetch_array($cnsMp))
    {
       $this->Cell($w[0],$h,$rstMp[mat_prim_tipo],1,0,'L'); 
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $rstTot=pg_fetch_array($Mp->listaSumaTotalIMPbyDate($from,$until));                  
       $tot=0;
       $tpor=0;
       while($rstSec=pg_fetch_array($cnsSec0))
       {
           $rstIMP=pg_fetch_array($Mp->listaSumIngMPbydatePro0($from,$until,$rstMp[mat_prim_tipo],$rstSec[sec_id]));           
           $rst=pg_fetch_array($Mp->listaSumaTotalIMPbyDateSec($from,$until,$rstSec[sec_id]));           
           $porc=$rstIMP[sum]*100/$rst[sum];
           $this->Cell($w[1],$h,number_format($rstIMP[sum],1),1,0,'R'); 
           $this->Cell($w[1],$h,number_format($porc,1),1,0,'R'); 
           $tot+=$rstIMP[sum];
       }
       $this->Cell($w[1],$h,number_format($tot,1),1,0,'R');
       $this->Cell($w[1],$h,number_format($tot*100/$rstTot[sum],1),1,0,'R'); 
       $this->Ln();  
    }
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->Cell($w[0],$h,'TOTAL:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rst=pg_fetch_array($Mp->listaSumaTotalIMPbyDateSec($from,$until,$rstSec[sec_id]));
            $this->Cell($w[1],$h,number_format($rst[sum],1),1,0,'R',true); 
            $this->Cell($w[1],$h,'',1,0,'R',true); 
            $tot+=$rst[sum];
           } 
       $this->Cell($w[1],$h,number_format($tot,1),1,0,'C',true); 
       $this->Cell($w[1],$h,'',1,0,'C',true); 
       $this->Ln();
//Desp y Rec
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->Cell($w[0],$h,'RECICLADO:',1,0,'L'); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rst=pg_fetch_array($Mp->lista_rep_rec_date_sec($from,$until,$rstSec[sec_id],'REC'));           
            $this->Cell($w[1],$h,number_format($rst[sum],1),1,0,'R'); 
            $this->Cell($w[1],$h,'',1,0,'R'); 
            $tot+=$rst[sum];
           } 
       $this->Cell($w[1],$h,number_format($tot,1),1,0,'C'); 
       $this->Cell($w[1],$h,'',1,0,'C'); 
       $this->Ln();
       
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->Cell($w[0],$h,'REPROCESADO:',1,0,'L'); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rst=pg_fetch_array($Mp->lista_rep_rec_date_sec($from,$until,$rstSec[sec_id],'REP'));           
            $this->Cell($w[1],$h,number_format($rst[sum],1),1,0,'R'); 
            $this->Cell($w[1],$h,'',1,0,'R'); 
            $tot+=$rst[sum];
           } 
       $this->Cell($w[1],$h,number_format($tot,1),1,0,'C'); 
       $this->Cell($w[1],$h,'',1,0,'C'); 
       $this->Ln();
       
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->SetFont('Arial','B',7);
       $this->Cell($w[0],$h,'TOTAL CONSUMO MATERIALES:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstREC=pg_fetch_array($Mp->lista_rep_rec_date_sec($from,$until,$rstSec[sec_id],'REC'));
            $rstREP=pg_fetch_array($Mp->lista_rep_rec_date_sec($from,$until,$rstSec[sec_id],'REP'));
            $rst=pg_fetch_array($Mp->listaSumaTotalIMPbyDateSec($from,$until,$rstSec[sec_id]));      
            $suma=$rstREC[sum]+$rstREP[sum]+$rst[sum];
            $this->Cell($w[1],$h,number_format($suma,1),1,0,'R',true); 
            $this->Cell($w[1],$h,'',1,0,'R',true); 
            $tot+=$suma;
           } 
       $this->Cell($w[1],$h,number_format($tot,1),1,0,'C',true);
       $this->Cell($w[1],$h,'',1,0,'C',true);
       $this->Ln();
       $this->SetFont('Arial','',7);
//Extrusion    
    $Extrusion=new Extrusion();
    $this->SetFont('Arial','B',8);
    $this->Cell($w[0]*7,$h,'PRODUCCION DE EXTRUSION',1,0,'C',true);$this->Ln();  
    $this->SetFont('Arial','',7);
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'A/D',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Extrusion->listExtbyDensidadSec($rstSec[sec_id],$from,$until,'t'));           
          $this->Cell($w[1]*2,$h,number_format($rstA[sum],1),1,0,'R'); 
          $tot+=$rstA[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'B/D',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstB=pg_fetch_array($Extrusion->listExtbyDensidadSec($rstSec[sec_id],$from,$until,'f'));           
          $this->Cell($w[1]*2,$h,  number_format($rstB[sum],1),1,0,'R'); 
          $tot+=$rstB[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'DESPERDIDCIO',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstD=pg_fetch_array($Mp->listDespByDateSecTpmq($from,$until,$rstSec[sec_id],1));           
          $this->Cell($w[1]*2,$h,  number_format($rstD[sum],1),1,0,'R'); 
          $tot+=$rstD[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->Cell($w[0],$h,'TOTAL:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstA=pg_fetch_array($Extrusion->listExtbyDensidadSec($rstSec[sec_id],$from,$until,'t'));                          
            $rstB=pg_fetch_array($Extrusion->listExtbyDensidadSec($rstSec[sec_id],$from,$until,'f'));                          
            $rstD=pg_fetch_array($Mp->listDespByDateSecTpmq($from,$until,$rstSec[sec_id],1));           
            $this->Cell($w[1]*2,$h,number_format($rstA[sum]+$rstB[sum]+$rstD[sum],1),1,0,'R',true); 
            $tot+=($rstA[sum]+$rstB[sum]+$rstD[sum]);
           } 
       $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R',true); 
       $this->Ln();
    
//Impresion
    $Impresion=new Impresion();
    $this->SetFont('Arial','B',8);
    $this->Cell($w[0]*7,$h,'PRODUCCION DE IMPRESION',1,0,'C',true);$this->Ln();  
    $this->SetFont('Arial','',7);
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'A/D',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Impresion->listaImpByDnsSecDate($rstSec[sec_id],$from,$until,'t'));           
          $this->Cell($w[1]*2,$h,number_format($rstA[peso],1),1,0,'R'); 
          $tot+=$rstA[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'B/D',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Impresion->listaImpByDnsSecDate($rstSec[sec_id],$from,$until,'f'));           
          $this->Cell($w[1]*2,$h,number_format($rstA[peso],1),1,0,'R'); 
          $tot+=$rstA[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'DESPERDIDCIO',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstD=pg_fetch_array($Mp->listDespByDateSecTpmq($from,$until,$rstSec[sec_id],2));           
          $this->Cell($w[1]*2,$h,  number_format($rstD[sum],1),1,0,'R'); 
          $tot+=$rstD[sum];
       } 
    $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R'); 
    $this->Ln();  
    
       $cnsSec0=$Sec->listaSeccionesPolietileno();
       $tot=0;
       $this->Cell($w[0],$h,'TOTAL:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstA=pg_fetch_array($Impresion->listaImpByDnsSecDate($rstSec[sec_id],$from,$until,'t'));           
            $rstB=pg_fetch_array($Impresion->listaImpByDnsSecDate($rstSec[sec_id],$from,$until,'f'));           
            $rstD=pg_fetch_array($Mp->listDespByDateSecTpmq($from,$until,$rstSec[sec_id],2));           
            $this->Cell($w[1]*2,$h,number_format($rstA[peso]+$rstB[peso]+$rstD[sum],1),1,0,'R',true); 
            $tot+=($rstA[peso]+$rstB[peso]+$rstD[sum]);
           } 
       $this->Cell($w[1]*2,$h,number_format($tot,1),1,0,'R',true); 
       $this->Ln();
    
//producto terminado AD
    $Sellado=new Sellado();
    $this->SetFont('Arial','B',8);
    $this->Cell($w[0]*7,$h,'PRODUCTO TERMINADO',1,0,'C',true);$this->Ln();  
    $this->SetFont('Arial','',7);
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $this->SetFont('Arial','B',7);    
    $this->Cell($w[0],$h,'ALTA DENSIDAD',1,0,'C',true); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $this->Cell($w[1],$h,'Kg',1,0,'C',true); 
          $this->Cell($w[1],$h,'Cant',1,0,'C',true); 
       } 
          $this->Cell($w[1],$h,'Kg',1,0,'C',true); 
          $this->Cell($w[1],$h,'Cant',1,0,'C',true); 
          $this->Ln();      
    $this->SetFont('Arial','',7);      
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'MILLAR',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','t'));           
          $this->Cell($w[1],$h,number_format($rstA[peso],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln();  
    
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'ROLLOS',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','t'));           
          $this->Cell($w[1],$h,number_format($rstA[peso],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln();  

    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'KILOS',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','t'));           
          $this->Cell($w[1],$h,number_format($rstA[peso],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln(); 
    //Totales
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'TOTAL:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstF=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','t'));           
            $rstR=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','t'));    
            $rstK=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','t'));
            $this->Cell($w[1],$h,number_format($rstF[peso]+$rstR[peso]+$rstK[peso],1),1,0,'R',true);
            $this->Cell($w[1],$h,'',1,0,'R',true);
            $tot+=($rstF[peso]+$rstR[peso]+$rstK[peso]);
           } 
    $this->Cell($w[1],$h,number_format($tot,1),1,0,'R',true); 
    $this->Cell($w[1],$h,'',1,0,'R',true); 
    $this->Ln();
    
//producto terminado BD
    $this->SetFont('Arial','B',7);
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $this->Cell($w[0],$h,'BAJA DENSIDAD',1,0,'C',true); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $this->Cell($w[1],$h,'Kg',1,0,'R',true); 
          $this->Cell($w[1],$h,'Cant',1,0,'R',true); 
       } 
          $this->Cell($w[1],$h,'Kg',1,0,'C',true); 
          $this->Cell($w[1],$h,'Cant',1,0,'C',true); 
          $this->Ln();    
    $this->SetFont('Arial','',7);      
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'MILLAR',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','f'));           
          $this->Cell($w[1],$h,number_format($rstA[peso],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln();  
    
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'ROLLOS',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','f'));           
          $this->Cell($w[1],$h,number_format($rstA[rollos],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln();  
    
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $totp=0;
    $totc=0;
    $this->Cell($w[0],$h,'KILOS',1,0,'L'); 
       while ($rstSec=pg_fetch_array($cnsSec0))
       {
          $rstA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','f'));           
          $this->Cell($w[1],$h,number_format($rstA[rollos],1),1,0,'R'); 
          $this->Cell($w[1],$h,number_format($rstA[cant],1),1,0,'R'); 
          $totp+=$rstA[peso];
          $totc+=$rstA[cant];
       } 
    $this->Cell($w[1],$h,number_format($totp,1),1,0,'R'); 
    $this->Cell($w[1],$h,number_format($totc,1),1,0,'R'); 
    $this->Ln();  
    
    //Totales
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'TOTAL:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstF=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','f'));           
            $rstR=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','f'));    
            $rstK=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','f'));
            $this->Cell($w[1],$h,number_format($rstF[peso]+$rstR[peso]+$rstK[peso],1),1,0,'R',true); 
            $this->Cell($w[1],$h,'',1,0,'R',true); 
            $tot+=($rstF[peso]+$rstR[peso]+$rstK[peso]);
           } 
    $this->Cell($w[1],$h,number_format($tot,1),1,0,'R',true); 
    $this->Cell($w[1],$h,'',1,0,'R',true); 
    $this->Ln();
    
    //Totales
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'TOTAL DESPERDICIO:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstD=pg_fetch_array($Mp->listDespByDateSecTpmq($from,$until,$rstSec[sec_id],3));               
            $this->Cell($w[1],$h,number_format($rstD[sum],1),1,0,'R',true); 
            $this->Cell($w[1],$h,'',1,0,'R',true); 
            $tot+=$rstD[sum];
           } 
    $this->Cell($w[1],$h,number_format($tot,1),1,0,'R',true); 
    $this->Cell($w[1],$h,'',1,0,'R',true); 
    $this->Ln();
    
    //Totales
    $this->SetFont('Arial','B',7);
    $cnsSec0=$Sec->listaSeccionesPolietileno();
    $tot=0;
    $this->Cell($w[0],$h,'TOTAL PROD TERMINADO:',1,0,'L',true); 
           while ($rstSec=pg_fetch_array($cnsSec0))
           {
            $rstFA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','t'));           
            $rstRA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','t'));    
            $rstKA=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','t'));
            $rstFB=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'FUNDAS','f'));           
            $rstRB=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'ROLLOS','f'));    
            $rstKB=pg_fetch_array($Sellado->listaSellByDateSecUnidad($from,$until,$rstSec[sec_id],'KILOS','f'));
            
            $this->Cell($w[1],$h,number_format($rstFA[peso]+$rstRA[peso]+$rstKA[peso]+$rstFB[peso]+$rstRB[peso]+$rstKB[peso],1),1,0,'R',TRUE); 
            $this->Cell($w[1],$h,'',1,0,'R',TRUE); 
            $tot+=($rstFA[peso]+$rstRA[peso]+$rstKA[peso]+$rstFB[peso]+$rstRB[peso]+$rstKB[peso]);
           } 
    $this->Cell($w[1],$h,number_format($tot,1),1,0,'R',TRUE); 
    $this->Cell($w[1],$h,'',1,0,'R',TRUE); 
    $this->Ln();
    
    
    
}    
function Footer()
{
	$this->SetY(-2);
	$this->SetFont('Arial','I',8);
	$this->Cell(0,0,'Pag '.$this->PageNo().'/{nb}',0,0,'L');
        $this->Ln();
        $this->Cell(0,0,'Impreso: '.date('d/m/Y H:i:s').' / Sistema de Control de Produccion (SCP)',0,0,'R');
}

function days($from,$until)
{
    $f=explode('/',$from);
    $u=explode('/',$until);
    $from = new DateTime($f[0].'-'.$f[1].'-'.$f[2]);
    $until = new DateTime($u[0].'-'.$u[1].'-'.$u[2]);
    $dias=$from->diff($until);
    return $dias->format('%d');
}


}
$pdf = new PDF();
$pdf->FPDF('L');
$pdf->AddPage();
$pdf->SetDisplayMode(85);
$pdf->resumen($from,$until);
$pdf->Output();


?>
