<?php

class __WSPro_SQLQuery
{
    public $MemNum = 0;
    public $QueryType = 0;
    public $SQLQueryString = "";
}

class __WSPro_Mail
{
    public $MailTO = "";
    public $MailSubject = "";
    public $MailMessage = "";
	public $MailTO_tis620 = "";
    public $MailSubject_tis620 = "";
    public $MailMessage_tis620 = "";
}

define("PACKET_TYPE_NONE", 0);
define("PACKET_TYPE_SQL", 1);
define("PACKET_TYPE_MAIL", 2);

define("SQL_TYPE_RANGAME",0);
define("SQL_TYPE_RANSHOP",1);
define("SQL_TYPE_RANUSER",2);

class WSPro
{
    protected $PathFile = "";
    protected $NumPacketSize = 0;
    protected $PacketType = array();
    protected $QueryData = array();
    protected $MailData = array();
    function AddSendMail( $mailto , $mailsubject , $mailmessage , $mailto_tis620 , $mailsubject_tis620 , $mailmessage_tis620 )
    {
        $__swsPro_Mail = new __WSPro_Mail;
        $__swsPro_Mail->MailTO = $mailto;
        $__swsPro_Mail->MailSubject = $mailsubject;
        $__swsPro_Mail->MailMessage = $mailmessage;
		$__swsPro_Mail->MailTO_tis620 = $mailto_tis620;
        $__swsPro_Mail->MailSubject_tis620 = $mailsubject_tis620;
        $__swsPro_Mail->MailMessage_tis620 = $mailmessage_tis620;
        
        $n = $this->NumPacketSize;
        $this->PacketType[ $n ] = PACKET_TYPE_MAIL;
        $this->MailData[ $n ] = $__swsPro_Mail;
        
        $this->NumPacketSize++;
    }
    function AddSQLQuery( $memnum , $querytype , $sqlquerystring )
    {
        $__swsPro_SQLQuery = new __WSPro_SQLQuery;
        $__swsPro_SQLQuery->MemNum = $memnum;
        $__swsPro_SQLQuery->QueryType = $querytype;
        $__swsPro_SQLQuery->SQLQueryString = $sqlquerystring;
        
        $n = $this->NumPacketSize;
        $this->PacketType[ $n ] = PACKET_TYPE_SQL;
        $this->QueryData[ $n ] = $__swsPro_SQLQuery;
        
        $this->NumPacketSize++;
    }
    
    function DumpToFile( )
    {
        $file = sprintf("%s\\%s-%d-%d.dat",$this->PathFile,date("Y-m-d H.i.s"),rand(0, 9999),rand(0, 9999));
        $cNeoSQLSerialFile = new CNeoSerialFile;
        $cNeoSQLSerialFile->OpenFile(FOR_WRITE, $file);
        $cNeoSQLSerialFile->WriteInt($this->NumPacketSize);
        
        for( $i = 0; $i < $this->NumPacketSize ; $i++ )
        {
            $cNeoSQLSerialFile->WriteInt($this->PacketType[$i]);
            switch( $this->PacketType[$i] )
            {
            case PACKET_TYPE_SQL:
                {
                    $pData = $this->QueryData[$i];
                    $cNeoSQLSerialFile->WriteInt($pData->MemNum);
                    $cNeoSQLSerialFile->WriteInt($pData->QueryType);
                    $cNeoSQLSerialFile->WriteString($pData->SQLQueryString,strlen($pData->SQLQueryString));
                }break;
            case PACKET_TYPE_MAIL:
                {
                    $pData = $this->MailData[$i];
                    $cNeoSQLSerialFile->WriteString($pData->MailTO,strlen($pData->MailTO));
                    $cNeoSQLSerialFile->WriteString($pData->MailSubject, strlen($pData->MailSubject));
                    $cNeoSQLSerialFile->WriteString($pData->MailMessage, strlen($pData->MailMessage));
					$cNeoSQLSerialFile->WriteString($pData->MailTO_tis620,strlen($pData->MailTO_tis620));
                    $cNeoSQLSerialFile->WriteString($pData->MailSubject_tis620, strlen($pData->MailSubject_tis620));
                    $cNeoSQLSerialFile->WriteString($pData->MailMessage_tis620, strlen($pData->MailMessage_tis620));
                }break;
            }
        }
        
        $cNeoSQLSerialFile->CloseFile();
    }
    
    function __construct( /*$PathFile*/ )
    {
        $this->PathFile = WSPRO_WORKING_FILEDATA;
    }
}

?>
