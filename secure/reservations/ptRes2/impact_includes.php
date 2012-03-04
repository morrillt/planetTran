<?php

if(!function_exists('get_member_fares')) {
function get_member_fares($id) {
        $query = "select sum(t.total_fare) as total_fare,
                  avg(t.total_fare) as avg_fare
                 from reservations r left join trip_log t
                 on r.resid=t.resid
                 where r.memberid='$id'
                 and t.pay_status=25
                group by r.memberid";
        $qresult = mysql_query($query);
        $row = mysql_fetch_assoc($qresult);

        $return = array('total_fare' => $row['total_fare'],
                        'avg_fare' => $row['avg_fare']);
        return $return;
}
}
