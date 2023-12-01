<?php



function admin_email() {

  $emailBody = '  <table>
<tr>
  <th>
    Contact Details
  </th>
</tr>
<tr>
  <td>
    Name:
  </td>
  <td>
    CONTACT NAME
  </td>
</tr>
<tr>
  <td>
    Email:
  </td>
  <td>
    CONTACT EMAIL
  </td>
</tr>
<tr>
  <td>
    Phone:
  </td>
  <td>
    CONTACT PHONE
  </td>
</tr>
<tr>
  <td>
    Message:
  </td>
</tr>
<tr>
  <td>
    [message]
  </td>
</tr>
</table>';

return $emailBody;
}

 ?>
