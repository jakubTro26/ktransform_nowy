<?php

namespace Visitor\Service;

class Ticket
{
    public $pdfFile = '';

    public function generateTicket($visitorData, $view, $folder = 'ticket') {

        $pdfFile = dirname(__FILE__) . '/../../../public/data/' . $folder . '/ticket_'.$visitorData['visitor_hash'] . '.pdf';

        //if(!file_exists($pdfFile)) {

            $visitorData['public_url'] = dirname(__FILE__) . '../../../../public';

			if($visitorData['system_form'] == 'career') {
				$pdfBody = $view->fetch('pdf/ticket_career.phtml', $visitorData);
			} else {
				$pdfBody = $view->fetch('pdf/ticket.phtml', $visitorData);
			}

            $mpdf = new \mPDF('utf-8', 'A5', 0, '', 5, 5, 5, 5, 0, 0);
            $mpdf->WriteHTML($pdfBody);
            $mpdf->Output($pdfFile, 'F');

//            $html2pdf = new \Html2Pdf('P', 'A4', 'en', false, 'UTF-8', array(0, 0, 0, 0));
//            $html2pdf->setDefaultFont('Arial');
//            $html2pdf->writeHTML($pdfBody);
//            $html2pdf->Output($pdfFile, 'F');
        //}

        return $pdfFile;
    }
	
	public function generateInvoice($visitorData, $view, $folder = 'invoice') {

         $pdfFile = dirname(__FILE__) . '/../../../public/data/' . $folder . '/faktura_' . str_replace('/', '_', $visitorData['invoice_name']) . '.pdf';

        if(!file_exists($pdfFile)) {

            $visitorData['public_url'] = dirname(__FILE__) . '../../../../public';
			$pdfBody = $view->fetch('pdf/invoice.phtml', $visitorData);

            $mpdf = new \mPDF('utf-8', 'A4', 0, '', 5, 5, 5, 5, 0, 0);

			$stylesheet  = '';
			$stylesheet .= file_get_contents($visitorData['public_url'] . '/assets/plugins/pace/pace-theme-flash.css');
			$stylesheet .= file_get_contents($visitorData['public_url'] . '/assets/plugins/boostrapv3/css/bootstrap.min.css');
			$stylesheet .= file_get_contents($visitorData['public_url'] . '/pages/css/pages.css');

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($pdfBody, 2);
            $mpdf->Output($pdfFile, 'F');
		}
		
        return $pdfFile;
    }
	
	public function mergePDFFiles(Array $filenames, $outFile) {

        $pdfFile = dirname(__FILE__) . '/../../../public/data/ticket/laczone/visitor_' . $outFile . '.pdf';

        $mpdf = new \mPDF('utf-8', 'A4', 0, '', 5, 5, 5, 5, 0, 0);
        if ($filenames) {

            $filesTotal = sizeof($filenames);
            $fileNumber = 1;
            $mpdf->SetImportUse();
            if (!file_exists($outFile)) {
                $handle = fopen($outFile, 'w');
                fclose($handle);
            }
            foreach ($filenames as $fileName) {
                if (file_exists($fileName)) {
                    $pagesInFile = $mpdf->SetSourceFile($fileName);
                    //print_r($fileName); die;
                    for ($i = 1; $i <= $pagesInFile; $i++) {
                        $tplId = $mpdf->ImportPage($i);
                        $mpdf->UseTemplate($tplId);
                        if (($fileNumber < $filesTotal) || ($i != $pagesInFile)) {
                            $mpdf->WriteHTML('<pagebreak />');
                        }
                    }
                }
                $fileNumber++;
            }
            $mpdf->Output($pdfFile, 'F');
        }

        return $pdfFile;
    }

}