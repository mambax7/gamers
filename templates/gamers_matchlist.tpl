<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
  <tr>
      <td class='bg6'>
          <table width='100%' border='0' cellpadding='0' cellspacing='0'>
             <{if $showselect|default:0 == 1}>
             <tr>
                 <td colspan=2>
                     <{include file='db:gamers_select.tpl' title=$lang_selecttitle caption=$lang_selectcaption submit=$lang_submit name=$lang_teamid options=$teams selected=$selected url=$url|default:''}>
                 </td>
             </tr>
             <{/if}>
             <tr class='head'>
                 <td><{$matchlistfor|default:''}><b><{$teamname|default:''}></b></td>
                 <td align='right'>
                     <{if $admin|default:'no' == "yes"}>
                          <a href = 'index.php?op=matchform'><{$addmatch}></a>
                     <{/if}>
                 </td>
             </tr>
          </table>
      </td>
  </tr>
  <tr>
      <td colspan='2'>
          <table width='100%' border='0' cellpadding='4' cellspacing='1'>
             <tr>
                 <th width='15%'><b><{$teamdate|default:''}></b></th>
                 <th><b><{$teamopponent|default:''}></b></th>
                 <th><b><{$teammatchtype|default:''}></b></th>
                 <th><b><{$teamresult|default:''}></b></th>
                  <{foreach item=thiscap from=$captions|default:null}>
                    <th><b><{$thiscap.caption}></b></th>
                  <{/foreach}>
                 <{if $isTeamMember|default:false === true}>
                      <th><b><{$teamy}></b></th>
                      <th><b><{$teamn}></b></th>
                      <th><b>?</b></th>
                      <th width='15'></th>
                 <{/if}>
             </tr>
             <{foreach item=thismatch from=$match|default:null}>
             <tr class='<{$thismatch.class}>'>
                 <td><{$thismatch.weekday}>-<{$thismatch.mdate}></td>
                 <td align=center>
                 <{if $isTeamMember|default:false === true}>
                      <a href='matchdetails.php?mid=<{$thismatch.mid}>'>
                 <{/if}>
                 <{$thismatch.opponent}>
                 <{if $isTeamMember === true}>
                      </a>
                 <{/if}>
                 </td>
                 <td><span style="color: <{$thismatch.matchcolor}>; "><{$thismatch.type}></span></td>
                 <td>
                 <{if $admin == "yes"}>
                      <a href='index.php?op=matchform&mid=<{$thismatch.mid}>' target='_self'>
                 <{/if}>
                 <{$thismatch.matchresult}></td>
                 <{foreach item=thismap from=$thismatch.map}>
                   <td align=center><span style="color: <{$thismap.color}>; "><{$thismap.name}></span></td>
                 <{/foreach}>
                 <{if $isTeamMember|default:false === true}>
                      <td><b><{$thismatch.yes}></b></td>
                      <td><b><{$thismatch.no}></b></td>
                      <td><b><{$thismatch.noreply}></b></td>
                      <td><a href='availability.php?mid=<{$thismatch.mid}>'>
                             <img src='images/<{$thismatch.pic}>.gif' width='15' height='20'>
                      </a></td>
                 <{/if}>
             </tr>
             <{/foreach}>
             <tr>
                 <th colspan='14'><b><{$teamwins|default:''}><{$wins|default:''}></b> - <b><{$teamlosses|default:''}><{$losses|default:''}></b> - <b><{$teamdraws|default:''}><{$draws|default:''}></b></th>
             </tr>
             <tr class='head'>
                 <td colspan='4'>
                 <{if $nextstart|default:0 > 10}>
                      <a href='index.php?teamid=<{$teamid}>&start=<{$prevstart}>'><{$lang_prevmatches|default:''}></a>
                 <{/if}>
                 </td>
                 <td colspan='8' align='right'>
                     <a href='index.php?teamid=<{$teamid|default:''}>&start=<{$nextstart|default:''}>'><{$lang_nextmatches|default:''}></a>
                 </td>
             </tr>
          </table>
      </td>
  </tr>
</table>
<{include file='db:system_notification_select.tpl'}>
