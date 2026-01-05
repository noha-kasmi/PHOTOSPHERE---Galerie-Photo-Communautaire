<?php

require_once '../app/Core/Entities/BasicUser.php';
require_once '../app/Core/Entities/ProUser.php';

$user1 = new BasicUser(1, 'noha', 'noha@test.com');
$user2 = new ProUser(2, 'sara', 'sara@test.com');

echo $user1->getRole(); // basicUser
echo $user2->getRole(); // proUser
