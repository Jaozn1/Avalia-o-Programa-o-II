<?php
require __DIR__.'/../bootstrap.php';
$p = conectar(); $ar = new AutorRepository($p); $lr = new LivroRepository($p);
function conferir(bool $ok, string $msg): void { if (!$ok) throw new RuntimeException($msg); echo "$msg: OK\n"; }
function mostrarTotal(RepositoryInterface $repo): void { echo 'Total: '.count($repo->listar()).PHP_EOL; }
$a = new Autor('Fabricio Bruno', 'Brasileira'); $b = new Autor('Marina Costa');
$ar->salvar($a); $ar->salvar($b);
$l = new Livro('Fundamentos de PHP', null, 2024);
$id = $lr->salvarComAutores($l, [$a->id(), $b->id()]);
echo "IDs: livro $id; autores {$a->id()}, {$b->id()}\n";
$r = $lr->buscar($id); conferir($r instanceof Livro && $r->ano() === 2024 && $r->id() === $id && $ar->buscar($a->id()) instanceof Autor, 'Busca e hidratacao');
conferir(count($r->autores()) === 2, 'Dois autores recuperados');
$r->alterarTitulo('PHP e banco de dados'); $lr->salvar($r); conferir($lr->buscar($id)->titulo() === 'PHP e banco de dados', 'Atualizacao');
mostrarTotal($ar); mostrarTotal($lr);
$antes = count($lr->listar()); $falha = new Livro('Teste rollback');
try { $lr->salvarComAutores($falha, [0]); throw new RuntimeException('Falha esperada nao ocorreu'); } catch (PDOException $e) { conferir(count($lr->listar()) === $antes && $falha->id() === null, 'Rollback'); }
try { $lr->excluir($id); throw new RuntimeException('FK nao protegeu'); } catch (PDOException $e) { echo "FK impediu exclusao com vinculo\n"; }
$lr->desvincularAutores($id); $lr->excluir($id); conferir($lr->buscar($id) === null, 'Exclusao'); $ar->excluir($a->id()); $ar->excluir($b->id());
