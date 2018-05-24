<?php

// when a user violate the rules we suspend their account for 3 days, after it we dissolve the suspension, we do this with event
$dissolveSuspensionQuery = $db->prepare("CREATE EVENT dissolve_suspension ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 3 DAYS DO CALL proc_dissolve_suspension(:username)");