<?php
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>Apprentice</b></th>
                <th><b>Review Date</b></th>
                <th><b>Standard</b></th>
                <th><b>Employer or Store</b></th>
                <th><b>Coach</b></th>
                <th><b>Manager or Mentor</b></th>
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
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th colspan="24"><b>Summary of progress</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="3"><b>Course % Progress to date</b></th>
                <td colspan="3">'.$data[6].'</td>
                <th colspan="3"><b>Course % Expected Progress according to Training Plan</b></th>
                <td colspan="3">'.$data[7].'</td>
                <th colspan="3"><b>Comments</b></th>
                <td colspan="9">'.$data[8].'</td>
            </tr>
            <tr>
                <th colspan="3"><b>OTJH Completed</b></th>
                <td colspan="3">'.$data[9].'</td>
                <th colspan="3"><b>Expected OTJH as per Training Plan</b></th>
                <td colspan="3">'.$data[10].'</td>
                <th colspan="3"><b>Comments</b></th>
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
                <th><b>E & D, H & S, Safeguarding & Learner Welfare (LDC to check understanding & link to a vocational context. Any issues must be actioned accordingly)</b></th>
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
                <th><b>Recap on actions from last month</b></th>
                <th><b>What impact has this had in your current job role/situation</b></th>
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
                <th><b>Details of Teaching & Learning activity undertaken today (include reference to modules and knowledge, skills & behaviours)</b></th>
                <th><b>Modules and K,S,B</b></th>
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
                <th><b>What impact will this have in your job role/situation</b></th>
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
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th colspan="3"><b>Functional Skills Progress</b></th>
            </tr>
            <tr>
                <th></th>
                <th><b>Learning today</b></th>
                <th><b>Target for next visit</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th><b>Math</b></th>
                <td>'.$data[17].'</td>
                <td>'.$data[18].'</td>
            </tr>
            <tr>
                <th><b>English</b></th>
                <td>'.$data[19].'</td>
                <td>'.$data[20].'</td>
            </tr>
        </tbody>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>ALN (Additional Learner Needs) Suppot delivered today</b></th>
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
                <th><b>Agreed actions & future skills development activity</b></th>
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
                <th><b>Coach/Tutor Feedback</b></th>
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
                <th><b>Apprentice Comments regarding their learning journey</b></th>
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
            <th><b>Employer comment on progress</b></th>
            <td>MonthlyActivityRecord-'.str_replace(' ','_',$fullname).'-'.str_replace(' ','_',$coursename).'-'.$data[1].'-EmployerComment.pdf</td>
        </tr>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <tr>
            <th><b>Date & time of next planned review</b></th>
            <td>'.date('H:m d-m-Y',(new DateTime($data[31]))->format('U')).'</td>
        </tr>
        <tr>
            <th><b>Remote / Face to Face</b></th>
            <td>'.$data[32].'</td>
        </tr>
    </table>
';
$pdf->writeHTML($html, true, false, false, false, '');
$html = '
    <table border="1" cellpadding="2">
        <thead>
            <tr>
                <th><b>Learner Signature</b></th>
                <th><b>Coach Signature</b></th>
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