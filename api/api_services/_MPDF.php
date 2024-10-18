<?php
$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';
global $debug_mode, $db;

class Pdf extends \Mpdf\Mpdf
{
    public function clipRect($x, $y, $width, $heigth)
    {
        $this->pages[$this->page] .= $this->_setClippingPath($x, $y, $width, $heigth);
    }

    public function endClip()
    {
        $this->pages[$this->page] .= ' Q ';
    }
}

class CustomMPDF{
    public $company      = null;
    public $company_id   = null;
    public $created_by = null;
    public $exported_by = null;
    public $pdf_title    = null;
    public $pdf_html     = null;
    public $aws_files    = null;
    public $attachments  = null;
    public $creation_date = null;
    public $creation_time = null;
    public $email_ids = null;
    public $email_subject = null;
    public $email_body = null;
    public $header_html=null;
    public $footer_html=null;
    public $custom_header=null;
    public $font_family="";
    public $est_no="";
    public $date="";
    public $exp="";
    public $m_id="";
    public $project_id="";
    public $tag_id="";
    public $record_id="";
    public $module_heading="";
    public $module_name="";
    public $page=0;
    public $cover_sheet_on=0;
    public $show_border=1;

    public static function GenerateCoversheetPDF($pdf_html,$est_id){
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
            'tempDir' => __DIR__ . '/upload/tmp'
        ]);
        $search_tag=['"Times New Roman"','"Comic Sans MS"','"Courier New"','Helvetica','Impact','Tahoma','Verdana','"Open Sans"','"Arial Black"'];
        $replace_tag=['timesnewroman','comicnew','cournew','helveticanew','impactnew','tahomanew','verdananew','opensans','arialblack'];
        $pdf_html=str_replace($search_tag,$replace_tag,$pdf_html);
        $ortmp=array();
        $mpdf->_setPageSize(strtoupper('Letter'), $ortmp);
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'arial';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->shrink_tables_to_fit = 0;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->encrypted = false;
        $html_footer='<table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:9pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;" border="0">
                    <tr>
                        <td style="font-size:11px;font-style: normal;">Page {PAGENO}</td>
                    </tr>

                </table>';
        $header_html='<style>
                        @page { sheet-size: Letter;footer:myfooter;}
                         p{padding-right:0px;margin-bottom:20px;margin-top:0px;}
                         .cover_sheet{margin:0px 15px 0px 0px;font-family: arial;}
                    </style>
                    <!--mpdf
                        <htmlpagefooter name="myfooter">
                                '.$html_footer.'
                        </htmlpagefooter>
                        <sethtmlpagefooter name="myfooter" value="on" show-this-page="1"/>
                    mpdf-->
                        ';

        $mpdf->WriteHTML($header_html." ".$pdf_html);
        $mpdf->Output("upload/tmp/Estimate_cover_sheet_".$est_id.".pdf",\Mpdf\Output\Destination::FILE);
        return true;
    }

    public static function GeneratePDF($user_id, $company_id, $pdf_title, $pdf_html, $creation_date, $creation_time, $aws_files, $attachments = [], $email_ids = [] , $email_subject = '', $email_body = '', $header_html = "", $footer_html = "", $custom_header = "",$font_family="",$est_no="",$date="",$exp="",$m_id="",$module_heading="",$cover_sheet_html=0,$est_id=0,$project_id=0,$tag_id=0,$module_name="",$record_id=0,$show_border=1){
        global $outpujson,$gh, $const;
        $this_class=new CustomMPDF();
        if($m_id==$const->timeCardModuleID && $custom_header==1){
            $ortmp='l';
        }
        else{
            $ortmp='p';
        }
        $pdf_template_color = $gh->current_user['pdf_template_color'];
        $pdf_html = preg_replace('#background-color:(.*?)#', 'background-color:'.$pdf_template_color.';', $pdf_html);
        $search_tag=['"Times New Roman"','"Comic Sans MS"','"Courier New"','Helvetica','font-family: Impact','Tahoma','Verdana','"Open Sans"','"Arial Black"'];
        $replace_tag=['timesnewroman','comicnew','cournew','helveticanew','font-family: impactnew','tahomanew','verdananew','opensans','arialblack'];
        $pdf_html=str_replace($search_tag,$replace_tag,$pdf_html);
        if($gh->read('debug',0)==4){
            echo $pdf_html;die;
        }
        $show_header_from_company_setting=$gh->current_company_app_settings['show_image_header_pdf'];
        $show_footer_from_company_setting=$gh->current_company_app_settings['show_image_footer_pdf'];
        $this_class->company_id   = $company_id;
        $this_class->created_by = $user_id;
        $this_class->pdf_title    = $pdf_title;
        $this_class->pdf_html     = $pdf_html;
        $this_class->creation_date = $creation_date;
        $this_class->creation_time = $creation_time;
        $this_class->aws_files    = $aws_files;
        $this_class->attachments  = $attachments;
        $this_class->email_ids  = $email_ids;
        $this_class->email_subject  = $email_subject;
        $this_class->email_body  = $email_body;
        $this_class->header_html = $header_html;
        $this_class->footer_html = $footer_html;
        $this_class->custom_header = $custom_header;
        $this_class->est_no = $est_no;
        $this_class->date = $date;
        $this_class->exp = $exp;
        $this_class->m_id = $m_id;
        $this_class->project_id = $project_id;
        $this_class->tag_id = $tag_id;
        $this_class->record_id = $record_id;
        $this_class->module_heading=$module_heading;
        $this_class->module_name=$module_name;
        $this_class->cover_sheet_html=$cover_sheet_html;
        $this_class->show_border=$show_border;
        if($cover_sheet_html==0){
            try {
                if(($show_header_from_company_setting==1 || $show_footer_from_company_setting==1) && $m_id!=$const->todoModuleID){
                    $mpdf = new \Mpdf\Mpdf([
                        'margin_left' => 0,
                        'margin_right' => 0,
                        'margin_top' => 40,
                        'margin_bottom' => 35,
                        'margin_header' => 0,
                        'margin_footer' => 0,
                        'tempDir' => __DIR__ . '/upload/tmp'
                    ]);
                }
                else{
                    $mpdf = new \Mpdf\Mpdf([
                        'margin_left' => 10,
                        'margin_right' => 10,
                        'margin_top' => 40,
                        'margin_bottom' => 35,
                        'margin_header' => 5,
                        'margin_footer' => 5,
                        'tempDir' => __DIR__ . '/upload/tmp'
                    ]);
                }
            } catch (Exception $e) {
                print_r($e);
            }
        }
        else{
            try {
                if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1){
                    $mpdf = new Pdf([
                        'margin_left' => 0,
                        'margin_right' => 0,
                        'margin_top' => 40,
                        'margin_bottom' => 35,
                        'margin_header' => 0,
                        'margin_footer' => 0,
                        'tempDir' => __DIR__ . '/upload/tmp'
                    ]);
                }
                else{
                    $mpdf = new Pdf([
                        'margin_left' => 10,
                        'margin_right' => 10,
                        'margin_top' => 40,
                        'margin_bottom' => 35,
                        'margin_header' => 5,
                        'margin_footer' => 5,
                        'tempDir' => __DIR__ . '/upload/tmp'
                    ]);
                }
            } catch (Exception $e) {
                print_r($e);
            }
            // $mpdf->SetCompression(false);
            // $this_class->page=$mpdf->page;
            $pagecount = $mpdf->SetSourceFile('upload/tmp/Estimate_cover_sheet_'.$est_id.'.pdf');
            for($i=1;$i<=$pagecount;$i++){
                $tplIdx = $mpdf->ImportPage($i);
                $mpdf->_setPageSize(strtoupper('Letter'), $ortmp);
                $mpdf->AddPage();
                $mpdf->useTemplate($tplIdx);
                $mpdf->endClip();
            }
            if (file_exists('upload/tmp/Estimate_cover_sheet_'.$est_id.'.pdf')) {
                //unlink('upload/tmp/Estimate_cover_sheet_'.$est_id.'.pdf');
            }

        }

        $mpdf->_setPageSize(strtoupper('Letter'), $ortmp);
        $header_html=$this_class->setHeaderFooter();
        $this_class->pdf_html=$header_html." ".$this_class->pdf_html;
        $mpdf->SetTitle($this_class->pdf_title);
        $mpdf->SetAuthor($this_class->pdf_title);
        $mpdf->SetSubject($this_class->pdf_title);
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'arial';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->shrink_tables_to_fit = 0;
        $mpdf->SetDisplayMode('fullpage');
        if($cover_sheet_html==1){
            $mpdf->AddPage();
        }
        /*if(gettype($this_class->pdf_html) == 'string') {
            $mpdf->writeHTML("<br><br>" . $this_class->pdf_html, true, false, true, false, '');
        }
        if(gettype($this_class->pdf_html) == 'array') {
            for($i=0;$i<count($this_class->pdf_html);$i++){
                if($i>0) {
                    // $this_class->lastPage();
                    $mpdf->AddPage();
                    //$this->pdf_html[$i] = '<br pagebreak="true"/>'.$this->pdf_html[$i];
                }
                $this_class->writeHTML("<br><br>" . $this_class->pdf_html[$i], true, false, true, false, '');
            }
        }*/
        $mpdf->WriteHTML($this_class->pdf_html);

        $this_class->ApplySettingsForAttachmentEmailAndOutput($mpdf,$this_class->attachments);

    }

    function merge_pdf($document_html)
    {
        ini_set('max_execution_time', '300');
        set_time_limit(300);
        ini_set('memory_limit','1024M');
        global $outpujson,$gh, $const;
        try{
            $mpdf = new Pdf([
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 40,
                'margin_bottom' => 35,
                'margin_header' => 5,
                'margin_footer' => 5,
                'tempDir' => __DIR__ . '/upload/tmp'
            ]);
        } catch (Exception $e) {
            echo $e;
        }
        $ortmp='p';
        $mpdf->_setPageSize(strtoupper('Letter'), $ortmp);
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'arial';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->shrink_tables_to_fit = 0;
        $mpdf->SetDisplayMode('fullpage');
        /*if($cover_sheet_html==1){
            $mpdf->AddPage();
        }*/
        $mpdf->WriteHTML(stripslashes($document_html));
        $pdf_name = __DIR__ . '/upload/tmp/'.time().'.pdf';
        // $pdf_name = time().'.pdf';
        $mpdf->Output($pdf_name,\Mpdf\Output\Destination::INLINE);
        // include_once('aws_upload_url.php');
        // $result=proceed_with_upload_base64(base64_encode($mpdf->Output($pdf_name,\Mpdf\Output\Destination::STRING_RETURN)),"",0,1,0,1,$gh->read('user_id',0),"document_writer",ucfirst("document_writer")."_".$gh->get_user_display_date(date('Y-m-d H:i:s'),"Y-m-d","H:i:s").".pdf");
        // print_r($result);
        die;
    }
    
    function create_bunch_pdf($arr_data, $pdf_title= "", $header_html="", $footer_html="", $custom_header="", $est_no="", $m_id="", $project_id=0, $record_id=0, $module_heading="", $module_name="")
    {
        ini_set('max_execution_time', '300');
        set_time_limit(300);
        ini_set('memory_limit','1024M');
        global $outpujson,$gh, $const;
        include_once('aws_upload_url.php');

        $this_class=new CustomMPDF();
        $pdf_template_color = $gh->current_user['pdf_template_color'];
        
        $search_tag=['"Times New Roman"','"Comic Sans MS"','"Courier New"','Helvetica','font-family: Impact','Tahoma','Verdana','"Open Sans"','"Arial Black"'];
        $replace_tag=['timesnewroman','comicnew','cournew','helveticanew','font-family: impactnew','tahomanew','verdananew','opensans','arialblack'];
        
        $show_header_from_company_setting=$gh->current_company_app_settings['show_image_header_pdf'];
        $show_footer_from_company_setting=$gh->current_company_app_settings['show_image_footer_pdf'];
        // vardump($gh->current_company['company_id']); die;
        $this_class->company_id   = $gh->current_user['company_id'];
        $this_class->created_by   = $gh->current_user["user_id"];
        $this_class->pdf_title    = $pdf_title;
        
        // $this_class->creation_date = $creation_date;
        // $this_class->creation_time = $creation_time;
        // $this_class->aws_files    = $aws_files;
        // $this_class->attachments  = $attachments;
        // $this_class->email_ids  = $email_ids;
        // $this_class->email_subject  = $email_subject;
        // $this_class->email_body  = $email_body;
        $this_class->header_html = null;
        $this_class->footer_html = $show_footer_from_company_setting == 1 ? $gh->current_company_app_settings['pdf_footer_image'] : '';
        if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1)
        {
            $this_class->custom_header = 1;
        } else {
            $this_class->custom_header = $custom_header;
        }
        $this_class->est_no = $est_no;
        // $this_class->date = $date;
        // $this_class->exp = $exp;
        $this_class->m_id = $m_id;
        $this_class->project_id = $project_id;
        // $this_class->tag_id = $tag_id;
        $this_class->record_id = $record_id;
        $this_class->module_heading=$module_heading;
        $this_class->module_name=$module_name;
        $this_class->cover_sheet_html="";
        $this_class->show_border=1;
        
 
        if(!empty($arr_data))
        {
            foreach($arr_data as $key => $val)
            {
                try{
                    
                    $mpdf = new \Mpdf\Mpdf([
                        'margin_left' => 10,
                        'margin_right' => 10,
                        'margin_top' => 40,
                        'margin_bottom' => 35,
                        'margin_header' => 5,
                        'margin_footer' => 5,
                        'tempDir' => __DIR__ . '/upload/tmp'
                    ]);
                    
                } catch (Exception $e) {
                    echo $e;
                }
                $ortmp='p';
                $mpdf->_setPageSize(strtoupper('Letter'), $ortmp);
                
                $pdf_html = "";
                $pdf_html = $val['document_html'];
                //$pdf_html = preg_replace('#background-color:(.*?)#', 'background-color:'.$pdf_template_color.';', $pdf_html);
                $pdf_html = str_replace($search_tag,$replace_tag,$pdf_html);
                if($gh->read('debug',0)==4){
                    echo $pdf_html;die;
                }
                $this_class->pdf_html = $pdf_html;
                $header_html = "";
                
                $header_html = $this_class->setHeader($val['add_header'],$val['add_footer']);
                
                $this_class->pdf_html = $header_html." ".$this_class->pdf_html;
                $mpdf->SetTitle($this_class->pdf_title);
                $mpdf->SetAuthor($this_class->pdf_title);
                $mpdf->SetSubject($this_class->pdf_title);

                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'arial';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
                $mpdf->shrink_tables_to_fit = 0;
                $mpdf->SetDisplayMode('fullpage');

                $mpdf->WriteHTML(stripslashes($this_class->pdf_html));
                
                $pdf_name = time().'.pdf';
                $result = [];
                $result=proceed_with_upload_base64(base64_encode($mpdf->Output($pdf_name,\Mpdf\Output\Destination::STRING_RETURN)),"",0,1,0,1,$gh->read('user_id',0),"document_writer",ucfirst("document_writer")."_".$gh->get_user_display_date(date('Y-m-d H:i:s'),"Y-m-d","H:i:s").".pdf");
                if(!empty($result) && $result['success'] == 1)
                {
                    $arr_data[$key]['final_url'] = $result['file_url'];
                }
            }
            $link_arr = [];
            foreach($arr_data as $k => $v)
            {
                if($v['final_url'] != "")
                {
                    $link_arr[] = $v['final_url'];
                }
                if(!empty($v['attached_files']))
                {
                    foreach($v['attached_files'] as $ke)
                    {
                        if($ke['file_url'] != "")
                        {
                            $link_arr[] = $ke['file_url'];
                        }
                    }
                }
            }
            // $link_string = implode(',',$link_arr);
            $parameter = json_encode(array(
                "url" => implode(',', $link_arr)
            ));
            $headers = array(   
                            'Content-Type: application/json',
                            'Connection: Keep-Alive',
                            'x-api-key: sailon@sailon.com_2cedcf84be3dae0fd9da8efebd2fc45b102d'
                        );
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, "https://api.pdf.co/v1/pdf/merge");
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $parameter);
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            // print_r($buffer); die;
            return $buffer;
        }
    }

    function merge_pdf_links($attached_files)
    {
        $temp_attechment_arr = [];
        if(!empty($attached_files))
        {
            $temp_files_arr = json_decode(stripslashes($attached_files), true);
            $count = 1; 
            foreach($temp_files_arr as $key => $val)
            {
                if($val['file_url'] != '')
                    $temp_attechment_arr[] = $val['file_url'];
            }
        }
        // print_r($temp_attechment_arr); die;
        $parameter = json_encode(array(
            "url" => implode(',', $temp_attechment_arr)
        ));
        $headers = array(   'Content-Type: application/json',
                            'Connection: Keep-Alive',
                            'x-api-key: dilip@weenggs.com_2cedcf84be3dae0fd9da8efebd2fc45b102d'
                        );
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, "https://api.pdf.co/v1/pdf/merge");
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $parameter);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        print_r($buffer);
        // die('oookkk');
        print_r($temp_attechment_arr); die;
    }

    /*
    *   for set only header in pdf
    */
    public function setHeader($add_header=0,$add_footer=0)
    {
        global $outputjson, $gh, $db, $const;
        /*
         * header start
         * */
        $current_user    = $gh->current_user;
        $current_company = $gh->current_company;
        $estimate_label=$gh->current_company_app_settings['estimate_selected_label'];
        $show_header_from_company_setting=$gh->current_company_app_settings['show_image_header_pdf'];
        $show_footer_from_company_setting=$gh->current_company_app_settings['show_image_footer_pdf'];
        $header_image=$gh->current_company_app_settings['pdf_header_image'];
        // print_r($gh->current_company_app_settings); die;
        $estimate_label=strtoupper($estimate_label)." #";
        if (count($current_user) > 0) {
            $this->exported_by = $current_user['first_name'] . ' ' . $current_user['last_name'];
        }
        $user_id = $this->created_by;
        if ($user_id > 0 && $user_id != '') {
            // check if current user is same as entry user..
            $current_user = $gh->current_user;
            if (count($current_user) > 0 && $current_user["user_id"] == $user_id) {
                $this->created_by = $current_user['first_name'] . ' ' . $current_user['last_name'];
            } else {
                $user = $db->execute("SELECT * FROM `tbl_users` WHERE user_id = " . $user_id . " LIMIT 0,1");
                if (count($user) > 0) {
                    $this->created_by = $user[0]['first_name'] . ' ' . $user[0]['last_name'];
                }
            }
        }

        $final_logo_width  = $logo_width  = $max_logo_width  = 230;
        $final_logo_height = $logo_height = $max_logo_height = 100;

        $company_details = $db->execute("SELECT * FROM tbl_companies WHERE company_id = " . $this->company_id . "");
        if (count($company_details) > 0) {
            $this->company       = $company_details[0];
            $gh->current_company = $company_details[0];
            if (empty($this->company['image'])) {
                $path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
                // $company_image                  = "TCPDF/img/logo-medium.png";
                $company_image                  = 'images/logo/logo-medium.png';
                list($logo_width, $logo_height) = getimagesize($company_image);
            } else {
                $company_image = $this->company['image'];
                $company_image = str_replace("/thumb/", "/large/", $company_image);
                if (empty($this->company["logo_width"])) {
                    list($logo_width, $logo_height) = getimagesize($company_image);
                } else {
                    $logo_width  = $this->company["logo_width"];
                    $logo_height = $this->company["logo_height"];
                }
            }
        }

        if ($logo_height > $max_logo_height) {
            $final_logo_height = $max_logo_height;
            $final_logo_width  = intval(($logo_width * $final_logo_height) / $logo_height);
        } else {
            $final_logo_height = $logo_height;
            $final_logo_width  = intval(($logo_width * $final_logo_height) / $logo_height);
        }
        $margin="";

        if($this->custom_header==1){
            if($show_header_from_company_setting==0 ){
                $html_header = '
                    <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:10pt;padding:0px;width:93%;border:0px #fff;margin-bottom: 10px;'.$margin.'" border="0">
                        <tr>
                            <td width="60%">
                                <table border="0" cellpadding="0" cellspacing="1" nobr="true" style="padding:0px;width:100%;border:0px #fff;" border="0">

                                </table>
                            </td>
                            <td align="right">
                                <img src="' . $company_image . '" alt=" " style="" width="' . $final_logo_width . 'px" height="' . $final_logo_height . 'px" />
                            </td>
                        </tr>
                    </table>
                    ';
            }
            else{
                list($width, $height)=getimagesize($header_image);
                if($height>100){
                    $height=100;
                }
                if($width>816){
                    $width=816;
                }
                $header_detail='<td align="center" width="100%"><img src="' . $header_image . '" align="center" alt=" " width="'.$width.'px" height="'.$height.'px" style=""/></td>';

                $html_header = '
                            <table border="0" cellpadding="0" cellspacing="0" nobr="true" width="100%" style="font-family:arial;font-size:10pt;padding:0px;border:0px #fff;" >
                                <tr>
                                    '.$header_detail.'
                                </tr>
                            </table>
                        ';
            }
        }
        else{
            if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1){
                $margin="padding-top:25px;padding-bottom:30px";
           }
            $html_header = '
                    <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:10pt;padding:0px;width:93%;border:0px #fff;margin-bottom: 10px;'.$margin.'" border="0">
                        <tr>
                            <td width="60%">
                                <table border="0" cellpadding="0" cellspacing="1" nobr="true" style="padding:0px;width:100%;border:0px #fff;" border="0">

                                </table>
                            </td>
                            <td align="right">
                                <img src="' . $company_image . '" alt=" " style="" width="' . $final_logo_width . 'px" height="' . $final_logo_height . 'px" />
                            </td>
                        </tr>
                    </table>
                    ';
        }
        /*
         * header end
         * */
        /*
         * footer start
         * */
        $company = $this->company;
        $company_address = $company['street'];

        $footer_image=$gh->current_company_app_settings['pdf_footer_image'];
        $company_extra = $gh->print_pdf_cell($this->company, 'company_name');
        if(!empty($company['city'])) {$company_address .= ', '.$company['city'];}
        if(!empty($company['state'])) {$company_address .= ', '.$company['state'];}
        if(!empty($company['zip_code'])) {$company_address .= ' '.$company['zip_code'];}
        if(!empty($company['license'])) {$company_address .= ', License # '.$company['license'].'';}
        if(!empty($company['tax_ein'])) {$company_address .= ', Business # '.$company['tax_ein'].'';}

        if(!empty($company['phone'])) {
            $company_extra .= ", Phone: " . $company['phone'];
        }
        if (!empty($company['fax'])) {
            $company_extra .= " Fax: " . $company['fax'];
        }

        $margin_bottom="";
        $padding_left="0px";
        if($this->custom_header==1 && $this->footer_html!=''){
            if($show_footer_from_company_setting==0 ) {
                $this->footer_html=str_replace('data-replace_text="[F_ADDRESS_LINE1]"'," ",$this->footer_html);
                $this->footer_html=str_replace('data-replace_text="[F_ADDRESS_LINE2]"'," ",$this->footer_html);
                $search_footer = ["[F_ADDRESS_LINE1]", "[F_ADDRESS_LINE2]"];
                $replace_footer = [
                    trim($company_extra, ',')
                    , trim($company_address, ',')
                ];
                $footer_detail = str_replace($search_footer, $replace_footer, $this->footer_html);
                if($show_footer_from_company_setting==0 && $show_header_from_company_setting==1) {
                    $margin_bottom="margin-bottom:20px;";
                }
                if($show_footer_from_company_setting==1 || $show_header_from_company_setting==1) {
                    $padding_left="30px;";
                }
                $html_footer = '
                <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:8pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;'.$margin_bottom.'" border="0">
                    <tr>
                    ' . $footer_detail . '
                    </tr>
                    <tr>

                        <td style="font-size:11px;font-style: normal;padding-left:'.$padding_left.';">Page {PAGENO}</td>
                    </tr>
                </table>
            ';
            }
            else{
                list($width, $height)=getimagesize($footer_image);
                if($height>82){
                    $height=82;
                }
                if($width>816){
                    $width=816;
                }
                $footer_detail='<td align="center" width="100%"><img src="' . $footer_image . '" align="center" alt=" " width="'.$width.'px" height="'.$height.'px" style=""  /></td>
            ';
                $html_footer = '
                <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:9pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;" border="0">
                    <tr>
                        <!--<td style="font-size:11px;padding-left:30px;font-style: normal;">Page {PAGENO}/{nbpg}</td>-->
                        <td style="font-size:11px;padding-left:30px;font-style: normal;">Page {PAGENO}</td>
                    </tr>
                    <tr>
                    ' . $footer_detail . '
                    </tr>
                </table>
            ';
            }
        }
        else{
            if($show_footer_from_company_setting==1) {
                $margin_bottom="margin-bottom:20px;";
            }
            $html_footer = '
            <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="text-align:center;font-family:arial;font-size:8pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;'.$margin_bottom.'" border="0">
                <tr>
                    <td style="font-size:11px;">' . trim($company_extra,',').'</td>
                </tr>
                <tr>
                    <td style="font-size:11px;">' . trim($company_address,',').'</td>
                </tr>
            </table>
        ';

        }
        $table_font_family="";
        $group_id = $gh->current_company_app_settings['module_group_id'];
        $currency = $db->execute_scalar("SELECT IFNULL(currency,'') FROM tbl_app_settings WHERE company_id ='".$this->company_id."'");
        if(empty($group_id)){
            $group_id = $db->execute_scalar('SELECT IFNULL(module_group_id,0) FROM tbl_companies WHERE company_id ="'.$this->company_id.'"');
        }

        //set font family for only those companies who either have english currency set or Fall into group Swift Dynamics
        if($group_id == $const->swiftDynamicsAccessGroupID || !in_array($currency, array('$', '£', '€'))){
            $table_font_family='
            table,table.main_table, .main_table th, p, .item_table tr td[data-item_id="1"] span{font-family: arial,dejavusanscondensed !important;}
            ';
        }
        else{
            $table_font_family='
            table,table.main_table, .main_table th, p, .item_table tr td[data-item_id="1"] span{font-family: arial;}
            ';
        }

        $html="";
        $main_border="";

        if($this->show_border){
            $main_border="1px solid";

        }
        else{
            $main_border="none";

        }

        $pdf_template_color = $gh->current_user['pdf_template_color'];
        $html_header = preg_replace('#background-color:(.*?)#', 'background-color:'.$pdf_template_color.';', $html_header);
        /*
         * footer end
         * */
        /*
         * 09-02-2021 by tristan added change by js : DBID #228402 > Estimate #20439 > Detailed w/ description > first page of PDF seems to be half empty.
         * */
        if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1){
            $html.='<style>
                        @page { header: myheader;footer:myfooter;}
                        .main_div{font-size:10pt;border:'.$main_border.';margin:40px 35px 40px 35px;}
                        .title_div{padding:5px;background-color: '.$pdf_template_color.';border-bottom:'.$main_border.';font-family: arial;font-weight: bold;font-size:10pt;}
                        .content_div{padding:5px;font-size:10pt;}
                        .cover_sheet,.heading{margin:40px 35px 40px 35px;}
                         .output_ul_header{font-size: 10pt;}
                         .tbl_t_and_c thead{display: table-row-group;}
                        table.main_table{margin:40px 35px 40px 35px;width:100%;font-size:10pt;}
                        .tbl_s_c_top,.inclusion_ul,.exclusion_ul,.scope_work_ul,.tbl_t_and_c,.signature_display,.output_ul_header,.tbl_note,.document_note_ul,.clarification_ul{border-collapse: collapse;border:'.$main_border.';}
                        .tbl_t_and_c thead th,.inclusion_ul thead th,.exclusion_ul thead th,.scope_work_ul thead th,.tbl_note thead th,.document_note_ul thead th,.clarification_ul thead th{border-bottom:'.$main_border.';}
                        .main_table th{text-align:left;}
                        .total_output th,.total_output td{font-size:10pt;}
                        .total_output tr th{font-weight: normal;width:75% !important;}
                        .total_output tr td{width:25% !important;}
                        .total_output tr:nth-child(3) th{font-weight: 700;}
                        .photos_section{page-break-inside: avoid;}
                        table.main_table.item_table {margin-top:0px !important;margin-bottom:5px !important;}
                        '.$table_font_family.'
                        p{padding-right:30px;margin-bottom:20px;margin-top:0px;}
                        p.co_total{margin-top:10px;}
                        .notes_title{font-size:10pt;font-family:arial;font-weight:bold;}
                        .heading_div{margin:0px 35px 0px 35px;}
                        .content_div ol li,.content_div ul li{font-family:arial;}
                        .c_finance_img{width:210px;height:60px;}
                        .client_financial_ouput tbody tr td a{color:#000 !important;font-size:10pt;font-family:arial;}
                        .custom_text_div p{margin-bottom:10px;}
                        .table-bordered {width: 100%; border-collapse: collapse;}
                        .table-bordered td, .table-bordered th {border: 1px solid #ddd;}
                    </style>';
        }
        else{
            $html.='<style>
                        @page { header: myheader;footer:myfooter;}
                        .main_div{border:'.$main_border.';margin:40px 0px 40px 0px;}
                        .title_div{padding:5px;background-color: '.$pdf_template_color.';border-bottom:'.$main_border.';font-family: arial;font-weight: bold;font-size:10pt;}
                        .content_div{padding:5px;font-size:10pt;}
                        .output_ul_header{font-size: 10pt;}
                        .cover_sheet,.heading{margin:40px 0px 40px 0px;}
                        .tbl_t_and_c thead{display: table-row-group;}
                        table.main_table{margin:40px 0px 40px 0px;width:100%;font-size:10pt;}
                        .tbl_s_c_top,.inclusion_ul,.exclusion_ul,.scope_work_ul,.tbl_t_and_c,.signature_display,.output_ul_header,.tbl_note,.document_note_ul,.clarification_ul{border-collapse: collapse;border:'.$main_border.';}
                        .tbl_t_and_c thead th,.inclusion_ul thead th,.exclusion_ul thead th,.scope_work_ul thead th,.tbl_note thead th,.document_note_ul thead th,.clarification_ul thead th{border-bottom:'.$main_border.';}
                        .main_table th{text-align:left;}
                        .total_output tr th{font-weight: normal;width:75% !important;}
                        .total_output tr td{width:25% !important;}
                        .total_output tr:nth-child(3) th{font-weight: 700;}
                        .total_output tr th,.total_output tr td{font-size:10pt;}
                        .photos_section{page-break-inside: avoid;}
                        table.main_table.item_table {margin-top:0px !important;margin-bottom:5px !important;}
                        '.$table_font_family.'
                        p{padding-right:0px;margin-bottom:20px;margin-top:0px;}
                        .notes_title{font-size:10pt;font-family:arial;font-weight:bold;}
                        .heading_div{margin:0px 0px 0px 0px;}
                        .content_div ol li,.content_div ul li{font-family:arial;}
                        .c_finance_img{width:210px;height:60px;}
                        .client_financial_ouput tbody tr td a{color:#000 !important;font-size:10pt;font-family:arial;}
                        .custom_text_div p{margin-bottom:10px;}
                        .table-bordered {width: 100%; border-collapse: collapse;}
                        .table-bordered td, .table-bordered th {border: 1px solid #ddd;}
                    </style>';
        }
        if($add_header==1)
        {
            $final_header = '<htmlpageheader name="myheader">
                            '.$html_header.'
                    </htmlpageheader>';
        } 
        if($add_footer==1) {
            $final_footer = '<htmlpagefooter name="myfooter">
                            '.$html_footer.'
                    </htmlpagefooter>';
        }
        $html.='
                <!--mpdf
                    '.$final_header.'
                    '.$final_footer.'
                    <sethtmlpageheader name="myheader" value="on" show-this-page="1"/>
                    <sethtmlpagefooter name="myfooter" value="on" />
                mpdf-->';
        return $html;
    }


    public function setHeaderFooter(){

        global $outputjson, $gh, $db, $const;
        /*
         * header start
         * */
        $current_user    = $gh->current_user;
        $current_company = $gh->current_company;
        $estimate_label=$gh->current_company_app_settings['estimate_selected_label'];
        $show_header_from_company_setting=$gh->current_company_app_settings['show_image_header_pdf'];
        $show_footer_from_company_setting=$gh->current_company_app_settings['show_image_footer_pdf'];
        $header_image=$gh->current_company_app_settings['pdf_header_image'];
        $estimate_label=strtoupper($estimate_label)." #";
        if (count($current_user) > 0) {
            $this->exported_by = $current_user['first_name'] . ' ' . $current_user['last_name'];
        }
        $user_id = $this->created_by;
        if ($user_id > 0 && $user_id != '') {
            // check if current user is same as entry user..
            $current_user = $gh->current_user;
            if (count($current_user) > 0 && $current_user["user_id"] == $user_id) {
                $this->created_by = $current_user['first_name'] . ' ' . $current_user['last_name'];
            } else {
                $user = $db->execute("SELECT * FROM `tbl_users` WHERE user_id = " . $user_id . " LIMIT 0,1");
                if (count($user) > 0) {
                    $this->created_by = $user[0]['first_name'] . ' ' . $user[0]['last_name'];
                }
            }
        }

        $final_logo_width  = $logo_width  = $max_logo_width  = 230;
        $final_logo_height = $logo_height = $max_logo_height = 100;

        $company_details = $db->execute("SELECT * FROM tbl_companies WHERE company_id = " . $this->company_id . "");
        if (count($company_details) > 0) {
            $this->company       = $company_details[0];
            $gh->current_company = $company_details[0];
            if (empty($this->company['image'])) {
                $path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
                // $company_image                  = "TCPDF/img/logo-medium.png";
                $company_image                  = 'images/logo/logo-medium.png';
                list($logo_width, $logo_height) = getimagesize($company_image);
            } else {
                $company_image = $this->company['image'];
                $company_image = str_replace("/thumb/", "/large/", $company_image);
                if (empty($this->company["logo_width"])) {
                    list($logo_width, $logo_height) = getimagesize($company_image);
                } else {
                    $logo_width  = $this->company["logo_width"];
                    $logo_height = $this->company["logo_height"];
                }
            }
        }

        if ($logo_height > $max_logo_height) {
            $final_logo_height = $max_logo_height;
            $final_logo_width  = intval(($logo_width * $final_logo_height) / $logo_height);
        } else {
            $final_logo_height = $logo_height;
            $final_logo_width  = intval(($logo_width * $final_logo_height) / $logo_height);
        }
            $margin="";

            if($this->custom_header==1){
                if($show_header_from_company_setting==0 ){
                    $address_header="";
                    if($this->m_id==$const->changeOrderModuleID){
                        if($this->module_heading!=""){
                            $estimate_label=strtoupper($this->module_heading)." #";
                        }

                        /*if(empty($this->date) || $this->date==""){
                            //$header_html=str_replace('data-replace_text="[HEADER_LOGO]"'," ",$this->header_html);
                            $html_new = preg_replace('/>\s*</im', '><', $this->header_html);
                            $dom = new DOMDocument;
                            $dom->loadHTML($html_new);
                            $dom->formatOutput = true;
                            $dom->preserveWhitespace = false;
                            // Use saveXML(), not saveHTML()
                            $html_new = $dom->saveHTML();
                            $header_html=preg_replace('#<th (.*?)>OWNER CO (.*?)</th>#', '', $html_new);
                            $header_html=preg_replace('#<td (.*?) data-item_id="2"(.*?)>(.*?)</td>#', '', $header_html);
                            $search_tag=["<html>","</html>","<body>","</body>"];
                            $replace_tag=["","","",""];
                            $this->header_html=str_replace($search_tag,$replace_tag,$header_html);
                        }
                        else{
                            $search_tag=["OWNER CO #"];
                            $replace_tag=["OWNER ".strtoupper($this->module_heading)." #"];
                            $this->header_html=str_replace($search_tag,$replace_tag,$this->header_html);
                        }*/
                    }

                    $address_header= $gh->print_pdf_cell($this->company, 'company_name')."<br>";
                    $address_header.= $gh->print_pdf_cell($this->company, 'phone')."<br>";
                    $address_header.= $gh->print_pdf_cell($this->created_by) . ' ' . $gh->print_pdf_cell($this->creation_date)."<br>";
                    $this->header_html=str_replace('data-replace_text="[HEADER_LOGO]"'," ",$this->header_html);
                    $search_header = ["[HEADER_LOGO]","[ESTIMATE_NO]","[DATE]","[EXPIRES]","[HEADER_SUB_ESTIMATE]"];
                    $replace_header   = [
                        '<img src="' . $company_image . '" alt=" " style="" width="' . $final_logo_width . 'px" height="' . $final_logo_height . 'px" />'
                        ,$this->est_no
                        , $this->date
                        , $this->exp
                        ,$estimate_label
                    ];

                    if($this->m_id==$const->estimateModuleID){
                        $search_header[]="ESTIMATE #";
                        $replace_header[]=$estimate_label;
                    }

                    $header_detail=str_replace($search_header,$replace_header,$this->header_html);
                    if($show_header_from_company_setting==0 && $show_footer_from_company_setting==1){
                         $margin="margin:30px;padding-top:25px;padding-bottom:30px";
                    }

                    $html_header = '
                        <table border="0" cellpadding="0" cellspacing="0" nobr="true" width="100%" style="font-family:dejavusanscondensed;font-size:10pt;padding:0px;border:0px #fff;'.$margin.'" >
                            <tr>
                                '.$header_detail.'
                            </tr>
                        </table>
                    ';
                }
                else{
                    list($width, $height)=getimagesize($header_image);
                    if($height>100){
                        $height=100;
                    }
                    if($width>816){
                        $width=816;
                    }
                    $header_detail='<td align="center" width="100%"><img src="' . $header_image . '" align="center" alt=" " width="'.$width.'px" height="'.$height.'px" style=""/></td>';

                    $html_header = '
                                <table border="0" cellpadding="0" cellspacing="0" nobr="true" width="100%" style="font-family:arial;font-size:10pt;padding:0px;border:0px #fff;" >
                                    <tr>
                                        '.$header_detail.'
                                    </tr>
                                </table>
                            ';
                }
            }
            else{
                if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1){
                    $margin="padding-top:25px;padding-bottom:30px";
               }
                $html_header = '
                        <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:10pt;padding:0px;width:93%;border:0px #fff;margin-bottom: 10px;'.$margin.'" border="0">
                            <tr>
                                <td width="60%">
                                    <table border="0" cellpadding="0" cellspacing="1" nobr="true" style="padding:0px;width:100%;border:0px #fff;" border="0">

                                    </table>
                                </td>
                                <td align="right">
                                    <img src="' . $company_image . '" alt=" " style="" width="' . $final_logo_width . 'px" height="' . $final_logo_height . 'px" />
                                </td>
                            </tr>
                        </table>
                        ';
            }
            /*
             * header end
             * */

            /*
             * footer start
             * */
        $company = $this->company;
        $company_address = $company['street'];

        $footer_image=$gh->current_company_app_settings['pdf_footer_image'];
        $company_extra = $gh->print_pdf_cell($this->company, 'company_name');
        if(!empty($company['city'])) {$company_address .= ', '.$company['city'];}
        if(!empty($company['state'])) {$company_address .= ', '.$company['state'];}
        if(!empty($company['zip_code'])) {$company_address .= ' '.$company['zip_code'];}
        if(!empty($company['license'])) {$company_address .= ', License # '.$company['license'].'';}
        if(!empty($company['tax_ein'])) {$company_address .= ', Business # '.$company['tax_ein'].'';}

        if(!empty($company['phone'])) {
            $company_extra .= ", Phone: " . $company['phone'];
        }
        if (!empty($company['fax'])) {
            $company_extra .= " Fax: " . $company['fax'];
        }

        $margin_bottom="";
        $padding_left="0px";
        if($this->custom_header==1 && $this->footer_html!=''){
            if($show_footer_from_company_setting==0 ) {
                $this->footer_html=str_replace('data-replace_text="[F_ADDRESS_LINE1]"'," ",$this->footer_html);
                $this->footer_html=str_replace('data-replace_text="[F_ADDRESS_LINE2]"'," ",$this->footer_html);
                $search_footer = ["[F_ADDRESS_LINE1]", "[F_ADDRESS_LINE2]"];
                $replace_footer = [
                    trim($company_extra, ',')
                    , trim($company_address, ',')
                ];
                $footer_detail = str_replace($search_footer, $replace_footer, $this->footer_html);
                if($show_footer_from_company_setting==0 && $show_header_from_company_setting==1) {
                    $margin_bottom="margin-bottom:20px;";
                }
                if($show_footer_from_company_setting==1 || $show_header_from_company_setting==1) {
                    $padding_left="30px;";
                }
                $html_footer = '
                <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:8pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;'.$margin_bottom.'" border="0">
                    <tr>
                    ' . $footer_detail . '
                    </tr>
                    <tr>

                        <td style="font-size:11px;font-style: normal;padding-left:'.$padding_left.';">Page {PAGENO}</td>
                    </tr>
                </table>
            ';
            }
            else{
                list($width, $height)=getimagesize($footer_image);
                if($height>82){
                    $height=82;
                }
                if($width>816){
                    $width=816;
                }
                $footer_detail='<td align="center" width="100%"><img src="' . $footer_image . '" align="center" alt=" " width="'.$width.'px" height="'.$height.'px" style=""  /></td>
            ';
                $html_footer = '
                <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="font-family:arial;font-size:9pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;" border="0">
                    <tr>
                        <!--<td style="font-size:11px;padding-left:30px;font-style: normal;">Page {PAGENO}/{nbpg}</td>-->
                        <td style="font-size:11px;padding-left:30px;font-style: normal;">Page {PAGENO}</td>
                    </tr>
                    <tr>
                    ' . $footer_detail . '
                    </tr>
                </table>
            ';
            }
        }
        else{
            if($show_footer_from_company_setting==1) {
                $margin_bottom="margin-bottom:20px;";
            }
            $html_footer = '
            <table border="0" cellpadding="0" cellspacing="0" nobr="true" style="text-align:center;font-family:arial;font-size:8pt;width:100%;font-style: italic; padding:0px; width:100%; border:0px #fff;color: gray;'.$margin_bottom.'" border="0">
                <tr>
                    <td style="font-size:11px;">' . trim($company_extra,',').'</td>
                </tr>
                <tr>
                    <td style="font-size:11px;">' . trim($company_address,',').'</td>
                </tr>
            </table>
        ';

        }
        $table_font_family="";
        $group_id = $gh->current_company_app_settings['module_group_id'];
        $currency = $db->execute_scalar("SELECT IFNULL(currency,'') FROM tbl_app_settings WHERE company_id ='".$this->company_id."'");
        if(empty($group_id)){
            $group_id = $db->execute_scalar('SELECT IFNULL(module_group_id,0) FROM tbl_companies WHERE company_id ="'.$this->company_id.'"');
        }

        //set font family for only those companies who either have english currency set or Fall into group Swift Dynamics
        if($group_id == $const->swiftDynamicsAccessGroupID || !in_array($currency, array('$', '£', '€'))){
            $table_font_family='
            table,table.main_table, .main_table th, p, .item_table tr td[data-item_id="1"] span{font-family: arial,dejavusanscondensed !important;}
            ';
        }
        else{
            $table_font_family='
            table,table.main_table, .main_table th, p, .item_table tr td[data-item_id="1"] span{font-family: arial;}
            ';
        }

        $html="";
        $main_border="";

        if($this->show_border){
            $main_border="1px solid";

        }
        else{
            $main_border="none";

        }

        $pdf_template_color = $gh->current_user['pdf_template_color'];
        $html_header = preg_replace('#background-color:(.*?)#', 'background-color:'.$pdf_template_color.';', $html_header);
            /*
             * footer end
             * */
        //.cover_sheet p{margin-top: 0;margin-bottom: 0;}
        //@page :first {header: no_header;}
        //@page { sheet-size: Letter;header: myheader;footer:myfooter;}
        //.title_div{padding:5px;background-color: #eeeeee;border-bottom:'.$main_border.';font-weight: bold;font-family: arial;font-size:10pt;}
        /*
         * 09-02-2021 by tristan added change by js : DBID #228402 > Estimate #20439 > Detailed w/ description > first page of PDF seems to be half empty.
         * */
        //        .main_div{font-size:10pt;border:'.$main_border.';margin:40px 35px 40px 35px;page-break-inside: avoid;}
        if($show_header_from_company_setting==1 || $show_footer_from_company_setting==1){
            $html.='<style>
                        @page { header: myheader;footer:myfooter;}
                        .main_div{font-size:10pt;border:'.$main_border.';margin:40px 35px 40px 35px;}
                        .title_div{padding:5px;background-color: '.$pdf_template_color.';border-bottom:'.$main_border.';font-family: arial;font-weight: bold;font-size:10pt;}
                        .content_div{padding:5px;font-size:10pt;}
                        .cover_sheet,.heading{margin:40px 35px 40px 35px;}
                         .output_ul_header{font-size: 10pt;}
                         .tbl_t_and_c thead{display: table-row-group;}
                        table.main_table{margin:40px 35px 40px 35px;width:100%;font-size:10pt;}
                        .tbl_s_c_top,.inclusion_ul,.exclusion_ul,.scope_work_ul,.tbl_t_and_c,.signature_display,.output_ul_header,.tbl_note,.document_note_ul,.clarification_ul{border-collapse: collapse;border:'.$main_border.';}
                        .tbl_t_and_c thead th,.inclusion_ul thead th,.exclusion_ul thead th,.scope_work_ul thead th,.tbl_note thead th,.document_note_ul thead th,.clarification_ul thead th{border-bottom:'.$main_border.';}
                        .main_table th{text-align:left;}
                        .total_output th,.total_output td{font-size:10pt;}
                        .total_output tr th{font-weight: normal;width:75% !important;}
                        .total_output tr td{width:25% !important;}
                        .total_output tr:nth-child(3) th{font-weight: 700;}
                        .photos_section{page-break-inside: avoid;}
                        table.main_table.item_table {margin-top:0px !important;margin-bottom:5px !important;}
                        '.$table_font_family.'
                        p{padding-right:30px;margin-bottom:20px;margin-top:0px;}
                        p.co_total{margin-top:10px;}
                        .notes_title{font-size:10pt;font-family:arial;font-weight:bold;}
                        .heading_div{margin:0px 35px 0px 35px;}
                        .content_div ol li,.content_div ul li{font-family:arial;}
                        .c_finance_img{width:210px;height:60px;}
                        .client_financial_ouput tbody tr td a{color:#000 !important;font-size:10pt;font-family:arial;}
                        .custom_text_div p{margin-bottom:10px;}
                    </style>';
        }
        else{
            $html.='<style>
                        @page { header: myheader;footer:myfooter;}
                        .main_div{border:'.$main_border.';margin:40px 0px 40px 0px;}
                        .title_div{padding:5px;background-color: '.$pdf_template_color.';border-bottom:'.$main_border.';font-family: arial;font-weight: bold;font-size:10pt;}
                        .content_div{padding:5px;font-size:10pt;}
                        .output_ul_header{font-size: 10pt;}
                        .cover_sheet,.heading{margin:40px 0px 40px 0px;}
                        .tbl_t_and_c thead{display: table-row-group;}
                        table.main_table{margin:40px 0px 40px 0px;width:100%;font-size:10pt;}
                        .tbl_s_c_top,.inclusion_ul,.exclusion_ul,.scope_work_ul,.tbl_t_and_c,.signature_display,.output_ul_header,.tbl_note,.document_note_ul,.clarification_ul{border-collapse: collapse;border:'.$main_border.';}
                        .tbl_t_and_c thead th,.inclusion_ul thead th,.exclusion_ul thead th,.scope_work_ul thead th,.tbl_note thead th,.document_note_ul thead th,.clarification_ul thead th{border-bottom:'.$main_border.';}
                        .main_table th{text-align:left;}
                        .total_output tr th{font-weight: normal;width:75% !important;}
                        .total_output tr td{width:25% !important;}
                        .total_output tr:nth-child(3) th{font-weight: 700;}
                        .total_output tr th,.total_output tr td{font-size:10pt;}
                        .photos_section{page-break-inside: avoid;}
                        table.main_table.item_table {margin-top:0px !important;margin-bottom:5px !important;}
                        '.$table_font_family.'
                        p{padding-right:0px;margin-bottom:20px;margin-top:0px;}
                        .notes_title{font-size:10pt;font-family:arial;font-weight:bold;}
                        .heading_div{margin:0px 0px 0px 0px;}
                        .content_div ol li,.content_div ul li{font-family:arial;}
                        .c_finance_img{width:210px;height:60px;}
                        .client_financial_ouput tbody tr td a{color:#000 !important;font-size:10pt;font-family:arial;}
                        .custom_text_div p{margin-bottom:10px;}
                    </style>';
        }

            $html.='
                    <!--mpdf
                        <htmlpageheader name="myheader">
                                '.$html_header.'
                        </htmlpageheader>

                        <htmlpagefooter name="myfooter">
                                '.$html_footer.'
                        </htmlpagefooter>

                        <sethtmlpageheader name="myheader" value="on" show-this-page="1"/>
                        <sethtmlpagefooter name="myfooter" value="on" />
                    mpdf-->';
        return $html;
    }
    public function ApplySettingsForAttachmentEmailAndOutput(&$mpdf, $attachmentFiles)
    {
        global $outputjson, $gh, $db, $const;
        $action = $gh->read('action', 'preview');
        ob_end_clean();
        $pdf_name = preg_replace("/[^A-Za-z0-9 ]/", '', $this->pdf_title);
        $pdf_name = (empty($pdf_name) ? $gh->read('op') : $pdf_name) . ".pdf";
        $is_bunch_pdf = $gh->read('is_bunch_pdf', '0');
        $bunch_pdf_link = $gh->read('bunch_pdf_link', '');
        
        if ($action == 'send') {
            $req_from = $gh->read('req_from','');
            if (empty($req_from)) {
                $emailList = $gh->read('emails', []);
            }
            elseif($req_from == 'we_pay'){
                $emailList = $gh->read('emails', '');
                if(!empty($emailList)){
                    $emailList = explode(',',$emailList);
                }
            }
            if ($req_from == 'send_to_supplier' || $req_from == 'send_to_approval' || $req_from == 'send_to_billed_to' || $req_from == 'approval_reply' || $req_from == 'send_rfi_request' || $req_from == 'send_submittal_request' || $req_from == 'send_submittal_response') {
                $emailList = $this->email_ids;
            }
            if(isset($emailList) && !empty($emailList)) {
                $email = $emailList[0];
                unset($emailList[0]);
                if($this->custom_header==1){
                    $subject = $this->pdf_title;
                }
                else{
                    //$subject = (empty($req_from)) ? $this->pdf_title : $this->email_subject;
                    $subject = (empty($req_from)) ? $this->pdf_title : stripslashes($gh->read('custom_subject', $this->email_subject));
                }
                $body = (empty($req_from)) ? $gh->read('custom_msg') : $this->email_body;
                if($req_from == 'we_pay'){
                    $custom_body_html = $gh->get_we_pay_email_body_html();
                    $final_logo_width  = $logo_width  = $max_logo_width  = 307;
                    $final_logo_height = $logo_height = $max_logo_height = 80;
                    $company_image = $gh->read('company_logo','');
                    if($company_image!="") {
                    // $company_image = $company_details[0]['image'];
                        $company_image = str_replace("/thumb/", "/large/", $company_image);
                        list($logo_width, $logo_height) = getimagesize($company_image);
                        if ($logo_height > $max_logo_height) {
                            $final_logo_height = $max_logo_height;
                            $final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
                        } else {
                            $final_logo_height = $logo_height;
                            $final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
                        }
                    }
                    $search_value = ["[COMPANY_LOGO]"
                        ,"[COMPANY_NAME]"
                        ,"[PAYEE_NAME]"
                        ,"[DISPLAY_NAME]"
                        ,"[ENTRY_DATE]"
                        ,"[AMOUNT]"
                        ,"[INV_NO]"
                        ,"[COMPANY_CONTACT]"
                        ,"[LOGO_WIDTH]"
                        ,"[LOGO_HEIGHT]"
                        ,"\n"
                        ,'&apos;'
                        ,'[PROCESS_NAME]'
                        ,'[EMAIL_TEXT]'
                        ,'[PAYMENT_METHOD]'
                    ];
                    $replace_value = [
                        $company_image
                        ,$gh->read('company_name','')
                        ,$gh->read('payee_name','')
                        ,$gh->read('dispaly_name','')
                        ,$gh->read('entry_date','')
                        ,$gh->read('amount','')
                        ,$gh->read('invoice_no','')
                        ,$gh->read('company_contact','')
                        ,$final_logo_width
                        ,$final_logo_height
                        ,""
                        ,''
                        ,$gh->read('process_name','')
                        ,$gh->read('email_text','')
                        ,$gh->read('payment_type_name','')
                    ];
                    $body = str_replace($search_value,$replace_value,$custom_body_html);
                }
                $text_body = '';
                $cc = $emailList;
                $bcc = array();

                //BHADRESH 2019-12-27 Global > Online Approval > If Online Approval is used, do not include the PDF in the sent email. 'send_to_approval' condition added
                $attachments = []; //Default set blank

                //22-Aug-2020: Estimate > Submit for Approval > Also include a PDF version with the email that has the approval link.=>So pdf_module_name condition added.
                $pdf_module_name = isset($this->module_name) ? $this->module_name : '';
                
                if(($req_from != 'send_to_approval' || $pdf_module_name == 'estimates') && $req_from != 'send_to_supplier'){

                    $attachments[] = array(
                    "Name" => $this->pdf_title . ".pdf"
                    , "ContentType" => "application/octet-stream"
                    , "Content" => base64_encode($mpdf->Output($pdf_name,\Mpdf\Output\Destination::STRING_RETURN)),
                    );

                    //if($gh->read('save_file_to_folder',0)==1 && $this->project_id>0){
                    if($gh->read('save_file_to_folder',0) == 1){
                        include_once('aws_upload_url.php');
                        $result=proceed_with_upload_base64(base64_encode($mpdf->Output($pdf_name,\Mpdf\Output\Destination::STRING_RETURN)),"",0,1,0,1,$gh->read('user_id',0),$this->module_name,ucfirst($this->module_name)."_".$gh->get_user_display_date(date('Y-m-d H:i:s'),"Y-m-d","H:i:s").".pdf");
                        if(!empty($result)){
                            $gh->addModuleFiles(array(json_encode($result)), (empty($this->project_id) ? $const->UNASSIGNED_FILES_STATIC_FOLDER : $this->m_id), $this->record_id, $this->project_id, $this->tag_id, 0, "", "", "", (empty($this->project_id) ? 1 : 0), 0, "", 1);
                        }
                    }
                    foreach($attachmentFiles as $attachment){
                        $attachments[] = array(
                            "Name" => $attachment['file_path']
                            , "ContentType" => "application/octet-stream"
                            , "Content" => base64_encode($mpdf->Output($attachment['file_path'],\Mpdf\Output\Destination::STRING_RETURN)),
                        );
                    }    
                }
                // for document bunch
                if($is_bunch_pdf){
                    $attachments = [];
                    $attachments[] = array(
                    "Name" => "test_bunch.pdf"
                    , "ContentType" => "application/octet-stream"
                    , "Content" => chunk_split(base64_encode(file_get_contents($bunch_pdf_link))),
                    );
                }

                $final_logo_width  = $logo_width  = $max_logo_width  = 307;
                $final_logo_height = $logo_height = $max_logo_height = 80;
                $current_company = $gh->current_company;
                $company_details = $db->execute("SELECT * FROM tbl_companies WHERE company_id = " . $current_company['company_id'] . "");
                if (count($company_details) > 0) {
                    $gh->current_company = $company_details[0];
                    $company_image = $company_details[0]['image'];
                    if (!empty($company_image)) {
                        $company_image = str_replace("/thumb/", "/large/", $company_image);

                        if (empty($this->company["logo_width"])) {
                            list($logo_width, $logo_height) = getimagesize($company_image);
                        } else {
                            $logo_width = $this->company["logo_width"];
                            $logo_height = $this->company["logo_height"];
                        }

                        if ($logo_height > $max_logo_height) {
                            $final_logo_height = $max_logo_height;
                            $final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
                        } else {
                            $final_logo_height = $logo_height;
                            $final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
                        }
                        //SG: 2019-09-20 Global > Send Email > Signature > If a user has added a logo to their account, the logo should show under the signature in the Sent email.  User 134612 reported that the logo does not show for SC and PO.

                        //BHADRESH: 2019-12-30 Estimate (All) > Online Approval > Email > The logo shows at the top (correct) and bottom (incorrect) of the email.=> Condition added

                        if($req_from != 'send_to_approval' && $req_from != 'send_to_supplier' && $req_from != 'approval_reply' && $req_from != 'send_rfi_request' && $req_from != 'send_submittal_request' && $req_from != 'send_submittal_response' && $req_from!= 'we_pay'){
                            // $body .= '<br><img title="Company logo" src="' . $company_image . '" style="width: ' . $final_logo_width . 'px;height: ' . $final_logo_height . 'px;object-fit: contain;" alt=""/>';
                            $body .= '<br><img title="Company logo" src="' . $company_image . '" width="' . $final_logo_width . '" height="' . $final_logo_height . '" style="object-fit: contain;" alt=""/>';
                        }

                    }
                }
                $response = $gh->send_pdf_via_email($email, $subject, $body, $text_body, $attachments, $cc, $bcc);
                $outputjson['email_response'] = $response;
            }
        } else if ($gh->read('from', PANEL_CONSTANT) == PANEL_CONSTANT) {
            if($gh->read("action","")=="download"){
                $outputjson['base64_encode_pdf'] = ("data:application/pdf;base64," . base64_encode($mpdf->Output($pdf_name, 'S')));
                $outputjson['success']=1;
                $outputjson['data']=array("success"=>1,"pdf_name"=>$pdf_name);
                return true;
            }
            else{
            // https://stackoverflow.com/a/46912902/1005741 FIND THE SOLUTION FROM THIS LINK
            $mpdf->Output($pdf_name,\Mpdf\Output\Destination::INLINE);
            // HR:2019-04-04
            // Acrobat reader ask to save changes to file when we close file.
            exit();
            }
        } else {
            $outputjson['base64_encode_pdf'] = ("data:application/pdf;base64," . base64_encode($mpdf->Output($pdf_name,\Mpdf\Output\Destination::STRING_RETURN)));
        }
    }
}
?>