        <table width='100%' border='0' cellpadding='0' cellspacing='0'>
               <tr>
                   <td colspan=2>
                       <form action='<{$xoops_requesturi}>' method='get'>
                       <table width='100%' class='outer' cellspacing='1'>
                              <tr>
                                  <th colspan='2'>
                                      <{$title}>
                                  </th>
                              </tr>
                              <tr valign='top' align='left'>
                                  <td class='head'>
                                      <{$caption}>
                                  </td>
                                  <td class='even'>
                                      <select  size='1' name='<{$name}>'>
                                               <{html_options options=$options selected=$selected}>
                                      </select>
                                  </td>
                              </tr>
                              <tr valign='top' align='left'>
                                  <td class='head'>
                                  </td>
                                  <td class='even'>
                                      <input type='submit' class='formButton' name='select'  id='select' value='<{$submit}>'>
                                  </td>
                              </tr>
                       </table>
			</form>
                   </td>
               </tr>
        </table>
