<?php

$dissolveSuspensionQuery = $db->prepare("CREATE EVENT dissolve_suspension ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 3 DAYS DO CALL proc_dissolve_suspension(:username)");