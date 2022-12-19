<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
       <tr>
           <td>
               <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                      <{if $showselect|default:0 == 1}>
                      <tr>
                          <td colspan=2>
                              <{include file='db:gamers_select.tpl' title=$lang_selecttitle caption=$lang_selectcaption submit=$lang_submit name=$lang_teamid options=$teams selected=$selected}>
                          </td>
                      </tr>
                      <{/if}>
                      <tr class='head'>
                          <td>
                              <{$lang_administrationof}> <b><{$teamname}></b> <{$lang_teamplaying}> <b><{$teamtype}></b>
                          </td>
                          <td align='right'>
                              <a href='roster.php?teamid=<{$teamid}>'><{$lang_teamroster}></a> |
                              <a href='positions.php?teamid=<{$teamid}>'><{$lang_posoverview}></a> |
                              <a href='mypositions.php?teamid=<{$teamid}>'><{$lang_teammypos}></a> |
                              <a href='avstats.php?teamid=<{$teamid}>'><{$lang_teamavailstats2}></a>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
       <tr>
           <td>
               <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                      <tr>
                          <th>
                              <{$lang_teamnickname}>
                          </th>
                          <th align=center>
                              <{$lang_teamrank}>
                          </th>
                          <th align=center>
                              <{$lang_teamstatus}>
                          </th>
                          <th align=center>
                              <{$lang_teamrank}>
                          </th>
                      <form method='post' action='memberadmin.php'>
                      </tr>
                      <{foreach item=member from=$teammembers}>
                      <tr class=<{$member.class}> >
                          <td>
                              <a href='profile.php?uid=<{$member.uid}>' target='_self'><{$member.uname}></a>
                          </td>
                          <td>
                              <span style="color: <{$member.rankcolor}; ">><{$member.rank}></span>
                          </td>
                          <td>
                              <select  size='1' name='user[<{$member.uid}>][status]'>
                                       <{html_options options=$allstatus selected=$member.statusid}>
                              </select>
                          </td>
                          <td>
                              <SELECT name='user[<{$member.uid}>][rank]'>
                                      <{html_options options=$allranks selected=$member.rankid }>
                              </SELECT>
                              <input type=hidden name='user[<{$member.uid}>][oldrank]' value=<{$member.rankid}> >
                              <input type=hidden name='user[<{$member.uid}>][oldstatus]' value=<{$member.statusid}> >
                          </td>
                      </tr>
                      <{/foreach}>
                      <tr class='head'>
                          <td colspan='4'>
                              <input type=hidden name='teamid' value=<{$teamid}>>
                              <input type=hidden name='op' value='update'>
                              <input type=submit value='Update'>
                              </form>
                          </td>
                      </tr>
                      <tr class='outer'>
                          <td colspan='6'>
                              <{$lang_teamtotalmembers}><b> <{$totalcount}></b> - <span style="color: <{$activecolor}>; "><{$lang_teamactiveplayers}><b> <{$activecount}></b></span>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
</table>
