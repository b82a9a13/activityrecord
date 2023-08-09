<?php
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('apprentice', $p).'</b></th>
                <th><b>'.get_string('review_date', $p).'</b></th>
                <th><b>'.get_string('standard', $p).'</b></th>
                <th><b>'.get_string('employer_os', $p).'</b></th>
                <th><b>'.get_string('coach', $p).'</b></th>
                <th><b>'.get_string('manager_om', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[0].'</td>
                <td>'.date('d/m/Y',strtotime($data[1])).'</td>
                <td>'.$data[2].'</td>
                <td>'.$data[3].'</td>
                <td>'.$data[4].'</td>
                <td>'.$data[5].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$comments = get_string('comments', $p);
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th colspan="24"><b>'.get_string('summary_op', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3"><b>'.get_string('course_ptd', $p).'</b></th>
                <td colspan="3">'.$data[6].'</td>
                <th colspan="3"><b>'.get_string('course_eptd', $p).'</b></th>
                <td colspan="3">'.$data[7].'</td>
                <th colspan="3"><b>'.$comments.'</b></th>
                <td colspan="9">'.$data[8].'</td>
            </tr>
            <tr>
                <th colspan="3"><b>'.get_string('otjh_c', $p).'</b></th>
                <td colspan="3">'.$data[9].'</td>
                <th colspan="3"><b>'.get_string('expected_otjh_aptp', $p).'</b></th>
                <td colspan="3">'.$data[10].'</td>
                <th colspan="3"><b>'.$comments.'</b></th>
                <td colspan="9">'.$data[11].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('safeguarding', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[23].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('health_as', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[33].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('equality_ad', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[34].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('information_aag', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[35].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('recap_act_title', $p).'</b></th>
                <th><b>'.get_string('what_impact_title', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[12].'</td>
                <td>'.$data[13].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('details_ot_title', $p).'</b></th>
                <th><b>'.get_string('modules_aksb', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[14].'</td>
                <td>'.$data[15].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('what_impactw_title', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[16].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
if($data[17] != '' || $data[18] != '' || $data[19] != '' || $data[20] != ''){
    $html = '
        <table border="1" cellpadding="2">
            <thead>
                <tr>
                    <th colspan="3"><b>'.get_string('functional_sp', $p).'</b></th>
                </tr>
                <tr>
                    <th></th>
                    <th><b>'.get_string('learning_t', $p).'</b></th>
                    <th><b>'.get_string('target_fnv', $p).'</b></th>
                </tr>
            </thead>
            <tbody>
    ';
    if($data[17] != '' || $data[18] != ''){
        $html .= '
                <tr>
                    <th><b>'.get_string('math', $p).'</b></th>
                    <td>'.$data[17].'</td>
                    <td>'.$data[18].'</td>
                </tr>
        ';
    }
    if($data[19] != '' || $data[20] != ''){
        $html .= '
                <tr>
                    <th><b>'.get_string('english', $p).'</b></th>
                    <td>'.$data[19].'</td>
                    <td>'.$data[20].'</td>
                </tr>
        ';
    }
    $html .= '
            </tbody>
        </table>
    ';
    $pdf->writeHTML($html, true, false, false, false, '');
}
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('aln_title', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[21].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('agreed_act_title', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[24].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('coach_otf', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[22].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('apprentice_com_title', $p).'</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>'.$data[25].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <tr>
            <th><b>'.get_string('employer_cop', $p).'</b></th>
            <td>MonthlyActivityRecord-'.str_replace(' ','_',$fullname).'-'.str_replace(' ','_',$coursename).'-'.$data[1].'-EmployerComment.pdf</td>
        </tr>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <tr>
            <th><b>'.get_string('date_at_title', $p).'</b></th>
            <td>'.date('H:m d-m-Y',(new DateTime($data[31]))->format('U')).'</td>
        </tr>
        <tr>
            <th><b>'.get_string('remote_ftf', $p).'</b></th>
            <td>'.$data[32].'</td>
        </tr>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>'.get_string('learner_s', $p).'</b></th>
                <th><b>'.get_string('coach_s', $p).'</b></th>
            </tr>
        </thead>
        <tbody>';
$html .= '<tr>';
$html .= ($data[27] != '1970-01-01' && $data[29]) ? '<td><img src="@'.preg_replace('#^data:image/[^;]+;base64,#', '', $data[29]).'"></td>' : '<td></td>';
$html .= ($data[28] != '1970-01-01' && $data[30]) ? '<td><img src="@'.preg_replace('#^data:image/[^;]+;base64,#', '', $data[30]).'"></td>' : '<td></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= ($data[27] != '1970-01-01') ? '<td>'.$data[27].'</td>' : '<td></td>';
$html .= ($data[28] != '1970-01-01') ? '<td>'.$data[28].'</td>' : '<td></td>';
$html .= '</tr>';
$html .= '
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->Output("ActivityRecord-$fullname-$coursename-$data[1].pdf");