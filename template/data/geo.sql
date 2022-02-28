drop function if exists geo_dist;

DELIMITER ;;
CREATE FUNCTION geo_dist (_mylat double, _mylng double, _lat double, _lng double) 

RETURNS double
DETERMINISTIC
begin

declare _r double;
declare _ndlat double;
declare _ndlng double;
declare _na double;
declare _nc double;
declare _dist double;

set _r = 6371;

set _ndlat = radians(_lat - _mylat);
set _ndlng = radians(_lng - _mylng);

set _na = sin(_ndlat/2)*sin(_ndlat/2) + cos(radians(_lat))*cos(radians(_mylat))*sin(_ndlng/2)*sin(_ndlng/2);
set _nc = 2 * atan2(sqrt(_na), sqrt(1-_na));
set _dist = _r * _nc;


return _dist;

end ;;
DELIMITER ;
