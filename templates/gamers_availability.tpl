<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
       <tr>
           <td class='bg6'>
               <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                      <tr class='head'>
                          <td><b><{$weekday}> <{$day}> - <{$teamsize}> <{$lang_teamvs}> <{$teamsize}> <{$ladder}></b>
                          </td>
                          <td align='right'>
                              <a href='index.php?teamid=<{$teamid}>' target='_self'><{$lang_teammatchlist}></a>
                              | <a href='matchdetails.php?mid=<{$mid}>'><{$lang_teammatchdetails}></a>
                              | <a href='positions.php?mid=<{$mid}>'><{$lang_teammatchpositions}></a>
                          </td>
                      </tr>
               </table>
           </td>
       </tr>
       <tr>
           <td class='even'>
               <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                      <tr>
                          <td colspan='3'>
                              <b><{$teamname}> <{$lang_against}> <{$opponent}> - <{$lang_teamat}> <span> <{$time}></span></b>
                          </td>
                      </tr>
                      <tr class='odd'>
                          <td colspan='3'>
                              <{foreach item=thismap from=$map}>
                                        <{$thismap.caption}> <b><{$thismap.name}></b>
                              <{/foreach}>
                          </td>
                      </tr>
                      <tr class='even'>
                          <td width='33%'>
                              <span style="color: <{$layout.perfect}>; ">
                              <b><{$lang_teamavailable}> (<{$yes}>)
                              </b>
                              </span>
                          </td>
                          <td width='33%'>
                              <span style="color: <{$layout.bad}>; ">
                              <b><{$lang_teamnotavailable}> (<{$no}>)
                              </b>
                              </span>
                          </td>
                          <td width='33%'>
                              <span style="color: <{$layout.good}>; ">
                              <b><{$lang_teamsubs}> (<{$notsure}>)
                              </b>
                              </span>
                          </td>
                      </tr>
                      <tr>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$available}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$notavailable}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$subs}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}> <{$avail.comment}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                      </tr>
                      <tr class='even'>
                          <td width='33%'>
                              <span style="color: <{$layout.perfect}>; ">
                              <b><{$lang_teamlatepositive}> (<{$lateyes}>)
                              </b>
                              </span>
                          </td>
                          <td width='33%'>
                              <span style="color: <{$layout.bad}>; ">
                              <b><{$lang_teamlatenegative}> (<{$lateno}>)
                              </b>
                              </span>
                          </td>
                          <td width='33%'>
                              <span style="color: <{$layout.good}>; ">
                              <b><{$lang_teamnoreply}> (<{$noreply}>)
                              </b>
                              </span>
                          </td>
                      </tr>
                      <tr>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$latepos}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$lateneg}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                          <td>
                              <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                                     <{foreach item=avail from=$notreplied}>
                                               <tr class='odd'>
                                                   <td>
                                                       <a href='profile.php?uid=<{$avail.id}>'><{$avail.name}></a>
                                                   </td>
                                               </tr>
                                     <{/foreach}>
                              </table>
                          </td>
                      </tr>
                      <tr>
                          <td class='foot' colspan='3'>
                              <{$greeting}>
                          </td>
                      </tr>
                      <{if $pending == 1}>
                      <tr class='odd'>
                          <td colspan='3'>
                              <form method='post' action='availability.php'>
                              <input type='hidden' name='matchid' value=<{$mid}> >
                              <input type='hidden' name='userid' value=<{$uid}> >
                              <input type='hidden' name='op' value=<{$action}> >
                              <input type='hidden' name='avid' value=<{$avid}> >
                              <input type='hidden' name='comment' value=<{$comment}> >
                              <{if $lock == 1}>
                                   <SELECT name='availability'>
                                   <{if $myav != 'Yes'}>
                                        <{if $myav != 'LateYes' }>
                                             <OPTION value='LateYes'>
                                                     <{$lang_teamavailable}>
                                             </OPTION>
                                        <{/if}>
                                   <{/if}>
                                   <{if $myav != 'No'}>
                                        <{if $myav != 'LateNo' }>
                                             <OPTION value='LateNo'>
                                                     <{$lang_teamnotavailable}>
                                             </OPTION>
                                        <{/if}>
                                   <{/if}>
                                   </SELECT>
                              <{else}>
                                      <SELECT name='availability'>
                                      <{if $myav != 'Yes'}>
                                           <OPTION value='Yes' <{$avcheck}> >
                                                   <{$lang_teamavailable}>
                                           </OPTION>
                                      <{/if}>
                                      <{if $myav != 'Sub'}>
                                           <OPTION value='Sub' <{$subcheck}> >
                                                   <{$lang_teamsub}>
                                           </OPTION>
                                      <{/if}>
                                      <{if $myav != 'No'}>
                                           <OPTION value='No' <{$navcheck}> >
                                                   <{$lang_teamnotavailable}>
                                           </OPTION>
                                      <{/if}>
                                      </SELECT>
                              <{/if}>
                              <input type=submit value='<{$action}>'>
                              </form>
                          </td>
                      </tr>
                      <{if $admin == 'Yes' }>
                      <tr>
                          <td colspan='3'>
                              <form method='post' action='availability.php'>
                              <input type='hidden' name='op' value='lock'>
                              <input type='hidden' name='matchid' value=<{$mid}> >
                              <input type='hidden' name='teamid' value=<{$teamid}> >
                              <{if $lock==0}>
                                   <input type='hidden' name='alock' value=1 >
                                   <input type=submit value='<{$lang_teamlockavail}>'>
                              <{else}>
                                   <input type='hidden' name='alock' value=0 >
                                   <input type=submit value='<{$lang_teamunlockavail}>'>
                              <{/if}>
                              </form>
                          </td>
                      </tr>
                      <tr>
                          <td colspan='3'>
                              <form method='post' action='availability.php'>
                              <input type='hidden' name='op' value='reset'>
                              <input type='hidden' name='matchid' value=<{$mid}> >
                              <input type='hidden' name='teamid' value=<{$teamid}> >
                              <input type=submit value='<{$lang_teamresetavail}>'>
                              </form>
                          </td>
                      </tr>
                      <tr>
                          <td colspan='3'>
                              <form method='post' action='availability.php'>
                              <input type='hidden' name='op' value='AvailOverride'>
                              <input type='hidden' name='matchid' value=<{$mid}> >
                              <SELECT name='uid'>
                              <{foreach item=player from=$players}>
                                        <OPTION value='<{$player.uid}>'>
                                                <{$player.uname}>
                                        </OPTION>
                              <{/foreach}>
                              </SELECT>
                              <SELECT name='availability'>
                                      <OPTION value='Yes'>
                                              <{$lang_teamavailable}>
                                      </OPTION>
                                      <OPTION value='Sub'>
                                              <{$lang_teamsub}>
                                      </OPTION>
                                      <OPTION value='No'>
                                              <{$lang_teamnotavailable}>
                                      </OPTION>
                              </SELECT>
                              <input type=submit value='<{$lang_teamoverride}>'>
                              </form>
                          </td>
                      </tr>
                      <{/if}>
                      <{/if}>
               </table>
           </td>
       </tr>
</table>
