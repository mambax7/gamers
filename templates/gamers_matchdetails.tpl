<table width="100%" border="0" cellspacing="5">
  <tr valign="top">
    <td width="50%">
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr class="head">
          <td><b><{$teamname}></b> <{$lang_opponent}> <b><{$opponent}></b></td>
          <td align='right'><a href='index.php?teamid=<{$teamid}>' target='_self'><{$lang_teammatchlist}></a> | <a href='positions.php?mid=<{$mid}>'><{$lang_matchpositions}></a> | <a href='availability.php?mid=<{$mid}>'><{$lang_availability}></a>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width='100%' border='0' cellpadding='4' cellspacing='1'>
             <tr>
                 <th colspan='3'><b><{$weekday}> <{$day}> - <{$lang_at}> <span> <{$time}></span> CET</b></th>
             </tr>
             <tr>
                 <td class='head'><nobr><{$lang_matchtype}></nobr></td>
                 <td class='even'><b><{$teamsize}> <{$lang_versus}> <{$teamsize}> <{$ladder}></b></td>
                 <td class="even" style="color: <{$matchresultcolor}>"><b><{$ourscoresum}> - <{$theirscoresum}></b></td>
             </tr>
             <{foreach item=mapdetails from=$map}>
             <tr>
                 <td class='head'><nobr><{$mapdetails.mapno}></nobr></td>
                 <td class='odd'>
                 <{if $mapdetails.tacid > 0}>
                      <a href='tactics.php?op=display&tacid=<{$mapdetails.tacid}>'>
                 <{/if}>
                 <{$mapdetails.mapname}>
                 <{if $mapdetails.tacid > 0}>
                      </a>
                 <{/if}>
                 </td>
                 <td class='odd'><span style="color: <{$mapdetails.color}>; "><{$mapdetails.ourscore}> - <{$mapdetails.theirscore}></span></td>
             </tr>
             <{/foreach}>
             <tr>
			<{if $ip}>
                 <td class='head'><{$lang_server}></td>
                 <td class='even'><b><{$servername}></b></td>
                 <td class='even'>(<{$ip}>:<{$port}>)</td>
			<{else}>
                 <td class='head'><{$lang_server}></td>
                 <td class='even' colspan='2'><b><{$servername}></b></td>
            <{/if}>
             </tr>
			<{if $review}>
             <tr>
                 <td class='head'><{$lang_review}></td>
                 <td class='odd' colspan='2'><{$review}></td>
             </tr>
             <{/if}>
      </table>
    </td>
  </tr>

<{if $pending !== true OR $screenshotnumber > 0 }>
  <tr>
    <td>
	<table border='0' cellpadding='0' cellspacing='0' valign='top'>
	  <tr>
	    <td>
	      <table border='0' cellpadding='0' cellspacing='0'>
			<tr class='head'>
			  <td colspan="<{$screenshotnumber}>"><b><{$lang_screenshots}></b></td>
			</tr>
			<tr class="odd">
			<{foreach item=thismap from=$map}>
			  <{if $thismap.screenshot}><td><a href="<{$upload_url}>/screenshots/<{$thismap.screenshot}>" target="_blank"><img src="<{$upload_url}>/screenshots/thumbs/<{$thismap.screenshot}>" alt="" border="0"></a>&nbsp;</td><{/if}>
			<{/foreach}>
			</tr>
	      </table>
	    </td>
	  </tr>
	</table>
    </td>
  </tr>
<{/if}>
<{if $isTeamMember == "yes"}>
  <tr>
    <td>
      <table border='0' cellpadding='0' cellspacing='0'>
             <{foreach item=thismap from=$map}>
               <{if $pending == 1 OR $thismap.linenumbers > 0 }>
                  <tr class='head'>
                      <td colspan=3><b><{$lang_lineupfor}><{$thismap.mapname}></b></td>
                  </tr>
               <{/if}>
               <{if $thismap.edit == "edit"}>
                  <{foreach item=lineup from=$thismap.lineup}>
                            <tr class="<{$lineup.class}>">
                                <td><b><{$lineup.uname}></b></td>
                                <td><{$lineup.posname}></td>
                                <td><{$lineup.posdesc}></td>
                            </tr>
                  <{/foreach}>
                  <{/if}>
                  <{if $admin == "yes" AND $pending === true}>
                       <tr class='outer'>
                           <td colspan=3><a href='matchdetails.php?op=lineup&amp;mid=<{$mid}>&amp;matchmapid=<{$thismap.matchmapid}>'>*<{$thismap.edit}> <{$lang_lineup}></a></td>
                       </tr>
                  <{elseif $thismap.edit=="set"}>
                       <tr class='outer'><td colspan=3><{$lang_nolineupyet}></td></tr>
                  <{/if}>
             <{/foreach}>
      </table>
    </td>
  </tr>
<{/if}>
</table>
<{include file='db:system_notification_select.tpl'}>
