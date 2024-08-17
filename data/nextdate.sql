drop function if exists nextdate;

DELIMITER ;;
CREATE FUNCTION nextdate (now_ varchar(255), month_ int, date_ int) 

returns varchar(255) 
deterministic

begin

declare stamp_ varchar(255);
declare yeara_ varchar(255);

set yeara_=year(from_unixtime(now_));
set stamp_=unix_timestamp(concat(yeara_,'-',month_,'-',date_));

if stamp_<=now_ then
	set stamp_=unix_timestamp(concat(yeara_+1,'-',month_,'-',date_));
end if;

return stamp_;

end ;;
DELIMITER ;
