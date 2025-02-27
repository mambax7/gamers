<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
       <tr>
           <td>
               <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                      <{if $showselect|default:0 == 1}>
                           <tr>
                               <td colspan=2>
                                   <{include file='db:select.tpl' title=$lang_selecttitle caption=$lang_selectcaption submit=$lang_submit name=$lang_teamid options=$teams selected=$selected url=$url}>
                               </td>
                           </tr>
                      <{/if}>
                      <{if $match|default:0 == 1}>
                      <tr class='head'>
                          <td>
                              <b><{$teamname}> <{$lang_teamversus}> <{$opponent}></b>
                          </td>
                          <td align='right'>
                              <a href='index.php?teamid=<{$teamid}>' target='_self'><{$lang_teammatchlist}></a> |
                              <a href='matchdetails.php?mid=<{$mid}>'> <{$lang_teammatchdetails}></a> |
                              <a href='availability.php?mid=<{$mid}>'> <{$lang_teammatchavailability}>
                          </td>
                      </tr>
                      <tr>
                          <th colspan='2' align=right>
                              <b>
                              <{foreach item=map from=$maps}>
                                           <{$map.caption}> <{$map.name}>
                              <{/foreach}>
                              </b>
                          </th>
                      </tr>

                      <{else}>
                      <tr class='head'>
                          <td align='right'>
                          <{if $admin=='Yes'}>
                              <a href='memberadmin.php?teamid=<{$teamid}>'><{$lang_teamadmin}></a> |
                          <{/if}>
                          <a href='roster.php?teamid=<{$teamid}>'><{$lang_teamroster}></a> |
                          <a href='mypositions.php?teamid=<{$teamid}>'><{$lang_teammypos}></a> |
                          <a href='avstats.php?teamid=<{$teamid}>'><{$lang_teamavailstats2}></a>
                          </td>
                      </tr>
                      <{/if}>
               </table>
           </td>
       </tr>
       <tr>
           <td class='bg7'>
               <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                      <{foreach item=table from=$tables}>
                                  <tr class='head'>
                                      <td colspan='<{$numcells}>'>
                                          <b><{$table.caption}></b>
                                      </td>
                                  </tr>
                                  <tr>
                                      <th>
                                          <{$lang_teamnickname}>
                                      </th>
                                      <{foreach item=position from=$teampos.posshort}>
                                                <th width='<{$width}>%' align=center>
                                                    <{$position}>
                                                </th>
                                      <{/foreach}>
                                      <{foreach item=position from=$teamskills.posshort}>
                                                <th width='<{$width}>%' align=center>
                                                    <{$position}>
                                                </th>
                                      <{/foreach}>
                                  </tr>
                                  <{foreach item=player from=$table.players}>
                                  <tr class='<{$player.class}>'>
                                      <td>
                                          <{if $admin == "Yes"}>
                                               <a href='mypositions.php?uid=<{$player.uid}>&teamid=<{$teamid}>' >
                                          <{/if}>
                                          <{$player.name}>
                                          <{if $admin == "Yes"}>
                                               </a>
                                          <{/if}>
                                      </td>
                                      <{foreach item=position from=$teampos.posshort}>
                                      <td>
                                          <{foreach item=pos from=$player.positions}>
                                          <{if $pos.posshort==$position}>
                                               <{if $pos.priority == 1}>
                                                    <img src='images/primary.gif'>
                                               <{elseif $pos.priority == 2}>
                                                    <img src='images/secondary.gif' height=15 width=15>
                                               <{elseif $pos.priority == 3}>
                                                    <img src='images/tertiary.gif' height=15 width=15>
                                               <{/if}>
                                          <{/if}>
                                          <{/foreach}>
                                      </td>
                                      <{/foreach}>
                                      <{foreach item=position from=$teamskills.posshort}>
                                      <td>
                                          <{foreach item=skill from=$player.positions}>
                                          <{if $skill.posshort==$position}>
                                               <img src='images/primary.gif'>
                                          <{/if}>
                                          <{/foreach}>
                                      </td>
                                      <{/foreach}>
                                  </tr>
                                  <{/foreach}>
                                  <tr>
                                      <th>
                                          <{$lang_teamnickname}>
                                      </th>
                                      <{foreach item=position from=$teampos.posshort}>
                                                <th width='<{$width}>%' align=center>
                                                    <{$position}>
                                                </th>
                                      <{/foreach}>
                                      <{foreach item=position from=$teamskills.posshort}>
                                                <th width='<{$width}>%' align=center>
                                                    <{$position}>
                                                </th>
                                      <{/foreach}>
                                      </tr>
                                      <tr class='even'>
                                          <td><{$lang_teamfirstpos}></td>
                                          <{foreach item=pricount from=$table.pricount}>
                                                    <td width='<{$width}>%' align=center><{$pricount}></td>
                                          <{/foreach}>
                                          <{foreach item=skillcount from=$table.skillcount}>
                                                    <td width='<{$width}>%' align=center><{$skillcount}></td>
                                          <{/foreach}>
                                      </tr>
                                      <tr class='odd'>
                                          <td><{$lang_teamsecondpos}></td>
                                          <{foreach item=seccount from=$table.seccount}>
                                                    <td width='<{$width}>%' align=center><{$seccount}></td>
                                          <{/foreach}>
                                          <td colspan=<{$numskills}> align=center>
                                              <img src='images/primary.gif'> <{$lang_teamprimaryposition}>
                                          </td>
                                      </tr>
                                      <tr class='even'>
                                          <td><{$lang_teamthirdpos}></td>
                                          <{foreach item=tercount from=$table.tercount}>
                                                    <td width='<{$width}>%' align=center><{$tercount}></td>
                                          <{/foreach}>
                                          <td colspan=<{$numskills}> align=center>
                                              <img src='images/secondary.gif' height=15 width=15> <{$lang_teamsecondary}>
                                              <img src='images/tertiary.gif' height=15 width=15> <{$lang_teamtertiary}>
                                          </td>
                                      </tr>
                      <{/foreach}>
               </table>
           </td>
       </tr>
</table>
