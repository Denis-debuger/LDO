<?php
declare(strict_types=1);

auth_logout();
flash_set('ok', 'Вы вышли из системы.');
redirect('home');
