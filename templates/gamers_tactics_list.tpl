<table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
    <tr>
      <td class='bg6'>
          <table width='100%' border='0' cellpadding='0' cellspacing='0'>
             <{if $showselect|default:0 == 1}>
             <tr>
                 <td colspan=2>
                     <{include file='db:gamers_select.tpl' title=$lang_selecttitle caption=$lang_selectcaption submit=$lang_submit name=$lang_teamid options=$teams selected=$selected url=$url}>
                 </td>
             </tr>
             <{/if}>
             <tr class='head'>
                 <td><{$matchlistfor|default:''}><b><{$teamname|default:''}></b></td>
                 <td align='right'>
                     <{if $admin == "yes"}>
                          <a href = 'index.php?op=matchform'><{$addmatch}></a>
                     <{/if}>
                 </td>
             </tr>
          </table>
      </td>
    </tr>
    <tr>
        <td>
            <table width='100%' border='0' cellpadding='2' cellspacing='1'>
                <tr class='head'>
                    <td colspan='<{$headspan}>'><{$smarty.const._MD_GAMERS_TACTICSFOR}> <b><{$team.teamname}></b> <{$smarty.const._MD_GAMERS_PLAYING}> <b><{$team.teamtype}></b></td>
                    <td align=right><a href='roster.php?teamid=<{$team.teamid}>'><{$smarty.const._MD_GAMERS_ROSTER}></a> | <a href='index.php?teamid=<{$team.teamid}>' target='_self'><{$smarty.const._MD_GAMERS_MATCHLIST}></a></td>
                </tr>
                <tr class='outer' align='center'>
                    <{foreach item=teamsize from=$teamsizes}>
                        <td colspan="<{$firstspan}>" ><b><{$teamsize}> <{$smarty.const._MD_GAMERS_VERSUS}> <{$teamsize}></b></td>
                    <{/foreach}>
                </tr>
                <{foreach item=mapname from=$maps key=mapid}>
                    <{if $mapname != "-Not Played-"}>
                        <tr class="<{cycle values='odd, even'}>">
                            <{foreach item=teamsize from=$teamsizes}>
                                <td>
                                    <{if isset($tactics[$mapid][$teamsize]) }>
                                        <{if $tactics[$mapid][$teamsize]->getVar('tacid') > 0}>
                                            <a href='tactics.php?op=display&tacid=<{$tactics[$mapid][$teamsize]->getVar("tacid")}>'>
                                        <{/if}>
                                        <{$mapname}>
                                        <{if $tactics[$mapid][$teamsize]->getVar('tacid') > 0}>
                                            </a>
                                        <{/if}>
                                        <{if $admin == 'Yes'}>
                                            </td>
                                            <td align='right'>
                                                <{if $tactics[$mapid][$teamsize]->getVar('tacid') > 0}>
                                                    <a href='tactics.php?op=mantactics&tacid=<{$tactics[$mapid][$teamsize]->getVar("tacid")}>'>
                                                        <img src='images/edit.gif' border='0' alt='Edit'>
                                                    </a>
                                                <{else}>
                                                    <a href='tactics.php?op=mantactics&mapid=<{$mapid}>&amp;teamid=<{$team.teamid}>&amp;teamsize=<{$teamsize}> '>
                                                        <img src='images/addtactic.gif' border='0' alt='Add'>
                                                    </a>
                                                <{/if}>
                                            </td>
                                        <{/if}>
                                    <{/if}>
                                </td>
                            <{/foreach}>
                        </tr>
                    <{/if}>
                <{/foreach}>
            </table>
        </td>
    </tr>
</table>
