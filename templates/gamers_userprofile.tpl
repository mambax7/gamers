<{if $user_ownpage === true}>

<form name="usernav" action="user.php" method="post">

<br>

<table width="70%" align="center" border="0">
  <tr align="center">
    <td><input type="button" value="<{$smarty.const._MD_GAMERS_EDITPROFILE}>" onclick="location='../../edituser.php'">
    <input type="button" value="<{$smarty.const._MD_GAMERS_AVATAR}>" onclick="location='../../edituser.php?op=avatarform'">
    <input type="button" value="<{$smarty.const._MD_GAMERS_INBOX}>" onclick="location='../../viewpmsg.php'">
    <input type="button" value="<{$smarty.const._MD_GAMERS_LOGOUT}>" onclick="location='../../user.php?op=logout'"></td>
  </tr>
</table>
</form>

<br>
<{/if}>
<table width="100%" border="0" cellspacing="5">
  <tr valign="top">
    <td width="50%">
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr>
          <th colspan="2" align="center"><{$lang_allaboutuser}></th>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_AVATAR}></td>
          <td align="center" class="even"><img src="<{$user_avatarurl}>" alt="Avatar"></td>
        </tr>
        <tr>
          <td class="head"><{$smarty.const._MD_GAMERS_REALNAME}></td>
          <td align="center" class="odd"><{$user_realname}></td>
        </tr>
        <tr>
          <td class="head"><{$smarty.const._MD_GAMERS_WEBSITE}></td>
          <td class="even"><{$user_websiteurl}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_EMAIL}></td>
          <td class="odd"><{$user_email}></td>
        </tr>
	<tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_PM}></td>
          <td class="even"><{$user_pmlink}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_ICQ}></td>
          <td class="odd"><{$user_icq}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_AIM}></td>
          <td class="even"><{$user_aim}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_YIM}></td>
          <td class="odd"><{$user_yim}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_MSNM}></td>
          <td class="even"><{$user_msnm}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_LOCATION}></td>
          <td class="odd"><{$user_location}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_OCCUPATION}></td>
          <td class="even"><{$user_occupation}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_INTEREST}></td>
          <td class="odd"><{$user_interest}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_EXTRAINFO}></td>
          <td class="even"><{$user_extrainfo}></td>
        </tr>
      </table>
    </td>
    <td width="50%">
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr valign="top">
          <th colspan="2" align="center"><{$smarty.const._MD_GAMERS_STATISTICS}></th>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_MEMBERSINCE}></td>
          <td align="center" class="even"><{$user_joindate}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_RANK}></td>
          <td align="center" class="odd"><{$user_rankimage}><br><{$user_ranktitle}></td>
        </tr>
        <tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_POSTS}></td>
          <td align="center" class="even"><{$user_posts}></td>
        </tr>
	<tr valign="top">
          <td class="head"><{$smarty.const._MD_GAMERS_LASTLOGIN}></td>
          <td align="center" class="odd"><{$user_lastlogin}></td>
        </tr>
      </table>
      <br>
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr valign="top">
          <th colspan="2" align="center"><{$smarty.const._MD_GAMERS_SIGNATURE}></th>
        </tr>
        <tr valign="top">
          <td class="even"><{$user_signature}></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="5">
  <tr valign="top">
    <td width="50%">
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr>
          <th colspan="2" align="center"><{$lang_profileforuser}></th>
        </tr>
        <{foreach item=team from=$thisteam}>
        <tr valign="top">
            <td class="head"><{$team.name}></td>
            <td align="center" class="even"><{$team.teamrank}> - <{$team.teamstatus}></td>
        </tr>
        <{/foreach}>
      </table>
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr valign="top">
          <th colspan="2" align="center"><{$smarty.const._MD_GAMERS_POSITIONS}></th>
        </tr>
        <{foreach item=thisposteam from=$thisteam}>
        <tr valign="top">
            <td class="head"><{$thisposteam.name}></td>
            <td align="left" class="even"><{$thisposteam.primary}><br><{$thisposteam.secondary}><br><{$thisposteam.tertiary}></td>
        </tr>
        <{/foreach}>
      </table>
    </td>
    <td width="50%">
      <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr valign="top">
          <th colspan="2" align="center"><{$smarty.const._MD_GAMERS_SKILLS}></th>
        </tr>
        <{foreach item=team from=$thisteam}>
        <tr valign="top">
            <td class="head"><{$team.name}></td>
            <td align="left" class="even">
            <{foreach item=skills from=$team.skills}>
                      <{$skills.skillname}><br>
            <{/foreach}>
            </td>
        </tr>
        <{/foreach}>
      </table>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="5">
  <tr valign="top">
    <td>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
  <tr valign="top">
      <th colspan="6" align="center"><{$smarty.const._MD_GAMERS_AVAILABILITIES}></th>
  </tr>
  <{if $teammember|default:false === true}>
       <{if $newmatches|default:false === true}>
            <tr class='head'><td><{$smarty.const._MD_GAMERS_PENDMATCH}></td><td><{$smarty.const._MD_GAMERS_GAMERS_}></td><td><{$smarty.const._MD_GAMERS_OPPONENT}></td><td><{$smarty.const._MD_GAMERS_MATCHTYPE}></td><td><{$smarty.const._MD_GAMERS_RESULT}></td><td><{$smarty.const._MD_GAMERS_AVAILABILITY}></td><tr>
            <{foreach item=thismatch from=$thismatch}>
                      <tr class="<{$thismatch.class}>"><td><{$thismatch.date}></td><td><{$thismatch.teamname}></td><td><a href='matchdetails.php?mid=<{$thismatch.matchid}>'><{$thismatch.opponent}></a></td><td><{$thismatch.size}> <{$thismatch.vs}> <{$thismatch.size}> <{$thismatch.type}></td><td><{$thismatch.result}></td><td><a href='availability.php?mid=<{$thismatch.matchid}>'><{$thismatch.availability}></a></td></tr>
            <{/foreach}>
       <{/if}>
       <{if $prevmatches|default:false === true}>
            <tr class='head'><td><{$smarty.const._MD_GAMERS_PREVMATCHES}></td><td><{$smarty.const._MD_GAMERS_TEAM}></td><td><{$smarty.const._MD_GAMERS_OPPONENT}></td><td><{$smarty.const._MD_GAMERS_MATCHTYPE}></td><td><{$smarty.const._MD_GAMERS_RESULT}></td><td><{$smarty.const._MD_GAMERS_AVAILABILITY}></td><tr>
            <{foreach item=thismatch from=$prevmatch}>
                      <tr class="<{$thismatch.class}>"><td><{$thismatch.date}></td><td><{$thismatch.teamname}></td><td><a href='matchdetails.php?mid=<{$thismatch.matchid}>'><{$thismatch.opponent}></a></td><td><{$thismatch.size}> <{$smarty.const._MD_GAMERS_VERSUS}> <{$thismatch.size}> <{$thismatch.type}></td><td><{$thismatch.result}></td><td><a href='availability.php?mid=<{$thismatch.matchid}>'><{$thismatch.availability}></a></td></tr>
            <{/foreach}>
       <{/if}>
  <{/if}>
  </table>
  </td>
  </tr>
</table>
