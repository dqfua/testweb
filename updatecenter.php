<?php
header('Content-Type: text/html; charset=windows-874');

if ( !defined("DEBUG") )
define("DEBUG",TRUE);

include("global.loader.php");

set_time_limit(0);
session_start();

CInput::GetInstance()->BuildFrom( IN_GET );

$password = CInput::GetInstance()->GetValueString( "password" , IN_GET );
$hash = CInput::GetInstance()->GetValueString( "hash" , IN_GET );
$amount = CInput::GetInstance()->GetValueInt( "amount" , IN_GET );
$status = CInput::GetInstance()->GetValueInt( "status" , IN_GET );

if(empty($_SERVER['REMOTE_ADDR']) || strcmp($_SERVER['REMOTE_ADDR'],$_CONFIG['tmpay']['access_ip']) != 0) die('ERROR|ACCESS_DENIED');
else if( strlen($password) == 14 && isset($status) && strcmp($hash,md5($password . $password)) == 0 && TmPay::misc_parsestring($password))
{
	$tmpay = new Tmpay;
	$tmpay->UpdateCard($password,$amount,$status);
	//CDebugLog::Write( sprintf("Status : %d Do This OK",$status) );
	if ( $status == 1 )
	{
		$MemNum = $tmpay->GetMemNumFrom( $password );
		//CDebugLog::Write( sprintf("MemNum : %d Do This OK",$MemNum) );
		if ( $MemNum )
		{
			$UserNum = $tmpay->GetUserNumFrom( $MemNum , $password );
			//CDebugLog::Write( sprintf("UserNum : %d Do This OK",$UserNum) );
			if ( $UserNum )
			{
				$UserID = UserInfo::GetUserIDFromUserNum( $MemNum , $UserNum );
				//CDebugLog::Write( sprintf("UserID : %s",$UserID) );
				if ( $UserID != "" )
				{
					$ParentID = UserInfo::GetParentIDFromUserID( $MemNum , $UserID );
					//CDebugLog::Write( sprintf("UserID : %s , ParentID : %s",$UserID,$ParentID) );
					if ( $ParentID != "" )
					{
						$nUserNumParentID = UserInfo::GetUserNumFromUserID( $MemNum , $ParentID );
						//CDebugLog::Write( sprintf("ParentID : %d",$nUserNumParentID) );
						if ( $nUserNumParentID )
						{
							$GetRePoint = $tmpay->GetRePointCard( $amount );
							$pUserParent = new CNeoUser;
							$pUserParent->SetUserNum( $nUserNumParentID );
							$BeforePoint = $pUserParent->GetUserPoint();
							$pUserParent->UpPoint( $GetRePoint );
							$AfterPoint = $pUserParent->GetUserPoint();
							CNeoLog::Log_RePointInviteFriends( $MemNum , $nUserNumParentID , $UserNum , $GetRePoint , $BeforePoint , $AfterPoint );
						}
					}
					//CDebugLog::Write( sprintf("Update Center : UserID(%s),ParentID(%s)" , $UserID,$ParentID) );
				}
				
				$RefillNum = $tmpay->GetRefillNumFrom( $MemNum , $password );
				//CDebugLog::Write( sprintf("RefillNum : %d Do This OK",$RefillNum) );
				if ( $RefillNum )
				{
					//CDebugLog::Write( sprintf("GiveItemToItemBankFromCardSys Do This OK") );
					CRanShop::GiveItemToItemBankFromCardSys(
						$MemNum
						, $UserNum
						, $RefillNum
						, $password
						, $amount-1
						);
				}
			}
		}
	}
}
?>