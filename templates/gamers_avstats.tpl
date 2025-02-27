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
                              <{$lang_teamavailstats}> <b><{$teamname}></b>
                          </td>
                          <td align='right'>
                              <{if $admin == 'Yes'}>
                                   <a href='memberadmin.php?teamid=<{$teamid}>'><{$lang_teamadmin}></a> |
                              <{/if}>
                                   <a href='roster.php?teamid=<{$teamid}>'><{$lang_teamroster}></a> |
                                   <a href='positions.php?teamid=<{$teamid}>'><{$lang_teamposoverview}></a> |
                                   <a href='mypositions.php?teamid=<{$teamid}>'><{$lang_teammypos}></a>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
       <tr>
           <td>
               <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                      <tr>
                          <th width='24%'>
                              <b><{$lang_teamnickname}></b>
                          </th>
                          <th width='10%'>
                              <b><{$lang_teamstatus}></b>
                          </th>
                          <th width='10%'>
                              <b><{$lang_teammatches}></b>
                          </th>
                          <th width='14%'>
                              <b><{$lang_teamavailable}></b>
                          </th>
                          <th width='14%'>
                              <b><{$lang_teamnotavailable}></b>
                          </th>
                          <th width='14%'>
                              <b><{$lang_teamsub}></b>
                          </th>
                          <th width='14%'>
                              <b><{$lang_teamnoreply}></b>
                          </th>
                      </tr>
               <{foreach item=teamplayer from=$players}>
                      <tr class=<{$teamplayer.class}> >
                          <td>
                              <a href='profile.php?uid=<{$teamplayer.uid}>' target='_self'><{$teamplayer.uname}></a>
                          </td>
                          <td align='center'>
                              <span style="color: <{$teamplayer.statuscolor}>; ">
                              <b><{$teamplayer.status}></b>
                              </span>
                          </td>
                          <td align='center'>
                              <{$teamplayer.av}>/<{$teamplayer.total}>
                          </td>
                          <td>
                              <span style="color: <{$teamplayer.avcolor}>; "><{$teamplayer.avperc}></span>
                          </td>
                          <td>
                              <span style="color: <{$teamplayer.navcolor}>; "><{$teamplayer.nav}></span>
                          </td>
                          <td>
                              <span style="color: <{$teamplayer.subcolor}>; "><{$teamplayer.sub}></span>
                          </td>
                          <td>
                              <span style="color: <{$teamplayer.noreplycolor}>; "><{$teamplayer.noreply}></span>
                          </td>
                      </tr>
               <{/foreach}>
                      <tr class='head'>
                          <td colspan=3>
                              <a href='avstats.php<{$link1}>'><{$link1txt}></a>
                          </td>
                          <td colspan=4>
                              <a href='avstats.php<{$link2}>'><{$link2txt}></a>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
</table>
<br>
