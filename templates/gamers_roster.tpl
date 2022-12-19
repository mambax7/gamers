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
                              <{$lang_teamrosterfor}> <b><{$teamname}></b> <{$lang_teamplaying}> <b><{$teamtype}></b>
                          </td>
                          <td align='right'>
                              <{if $admin == 'Yes'}>
                                   <a href='memberadmin.php?teamid=<{$teamid}>'><{$lang_teamadmin}></a> |
                              <{/if}>
                              <{if $teammember|default:'No' == 'Yes'}>
                                   <a href='mypositions.php?teamid=<{$teamid}>'><{$lang_teammypos}></a> |
                                   <a href='positions.php?teamid=<{$teamid}>'><{$lang_teamposoverview}></a> |
                                   <a href='avstats.php?teamid=<{$teamid}>'><{$lang_teamavailstats2}></a>
                              <{/if}>
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
                          </th>
                          <th>
                              <b><{$lang_teamnickname}></b>
                          </th>
                          <th>
                              <b><{$lang_teamnationality}></b>
                          </th>
                          <th>
                              <b><{$lang_teamrank}></b>
                          </th>
                          <th>
                              <b><{$lang_teammembersince}></b>
                          </th>
                          <th align=center>
                              <b><{$lang_teamstatus}></b>
                          </th>
                      </tr>
               <{foreach item=teamplayer from=$players}>
                      <tr class="<{$teamplayer.class}>" >
                          <td>
                              <img src='<{$xoops_url}>/uploads/<{$teamplayer.avatar}>' alt='<{$teamplayer.bio}>' width='45'>
                          </td>
                          <td>
                              <a href='profile.php?uid=<{$teamplayer.uid}>' target='_self'><{$teamplayer.uname}></a>
                          </td>
                          <td>
                              <{if $teamplayer.flag|default:'' == 'Yes'}>
                                   <img src='assets/images/flags/<{$teamplayer.user_from}>.gif' alt='<{$teamplayer.user_from}>' width='20'>
                              <{elseif $teamplayer.flag|default:'' == 'No'}>
                                   <{$teamplayer.user_from}>
                              <{else}>
                                   <img src='assets/images/flags/generic.gif' alt='None' width='20'>
                              <{/if}>
                          </td>
                          <td>
                              <span style="color: <{$teamplayer.rankcolor}>; "><{$teamplayer.rank}></span>
                          </td>
                          <td>
                              <{$teamplayer.JoinedDate}>
                          </td>
                          <td align='center'>
                              <span style="color: <{$teamplayer.statuscolor}>; ">
                              <b><{$teamplayer.status}></b>
                              </span>
                          </td>
                      </tr>
               <{/foreach}>
                      <tr class='outer'>
                          <td colspan='6'>
                              <{$lang_teamtotalmembers}> <b><{$count}></b> -
                              <span style="color: <{$goodcolor}>; ">
                              <{$lang_teamactiveplayers}> <b><{$actives}></b>
                              </span>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
</table>
<br>
