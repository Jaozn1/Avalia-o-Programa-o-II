<?php
require __DIR__.'/../bootstrap.php';
$a = new Autor('Fabricio Bruno');
$l = new Livro('Introducao a PHP', null, 2024);
$l->adicionarAutor($a); $l->adicionarAutor($a);
if (count($l->autores()) !== 1) throw new RuntimeException('Autor duplicado');
$l->identificar(1);
try { $l->identificar(2); throw new RuntimeException('Identidade alterada'); } catch (LogicException $e) {}
echo "Entidades: OK\n";
