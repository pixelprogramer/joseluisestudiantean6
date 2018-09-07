<?php
class pixel_TCPDF extends Graficos{
    function inicializar(){
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
    }
    function nuevaPagina($orientation='', $format='', $keepmargins=false, $tocpage=false) {
        $this->inicializar();
        $this->AddPage($orientation, $format, $keepmargins, $tocpage);
    }
}