<?php
declare(strict_types=1);
interface RepositoryInterface {
    public function buscar(int $id): ?object;
    public function listar(): array;
    public function salvar(object $objeto): int;
    public function excluir(int $id): void;
}
abstract class AbstractRepository implements RepositoryInterface {
    public function __construct(protected PDO $pdo) {}
    abstract protected function tabela(): string;
    abstract protected function dados(object $objeto): array;
    abstract protected function hidratar(array $linha): object;
    protected function consulta(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt;
    }
    public function buscar(int $id): ?object {
        $linha = $this->consulta('SELECT * FROM '.$this->tabela().' WHERE id = ?', [$id])->fetch();
        return $linha === false ? null : $this->hidratar($linha);
    }
    public function listar(): array {
        return array_map(fn($linha) => $this->hidratar($linha), $this->consulta('SELECT * FROM '.$this->tabela().' ORDER BY id')->fetchAll());
    }
    public function salvar(object $objeto): int {
        $dados = $this->dados($objeto);
        $colunas = array_keys($dados);
        if ($objeto->id() === null) {
            $marcas = implode(',', array_fill(0, count($dados), '?'));
            $this->consulta('INSERT INTO '.$this->tabela().' ('.implode(',', $colunas).') VALUES ('.$marcas.')', array_values($dados));
            $objeto->identificar((int)$this->pdo->lastInsertId());
        } else {
            $campos = implode(',', array_map(fn($c) => "$c = ?", $colunas));
            $this->consulta('UPDATE '.$this->tabela().' SET '.$campos.' WHERE id = ?', [...array_values($dados), $objeto->id()]);
        }
        return $objeto->id();
    }
    public function excluir(int $id): void { $this->consulta('DELETE FROM '.$this->tabela().' WHERE id = ?', [$id]); }
}
final class AutorRepository extends AbstractRepository {
    protected function tabela(): string { return 'autores'; }
    protected function dados(object $objeto): array {
        if (!$objeto instanceof Autor) throw new InvalidArgumentException('Esperado Autor');
        return ['nome'=>$objeto->nome(), 'nacionalidade'=>$objeto->nacionalidade()];
    }
    protected function hidratar(array $linha): Autor {
        $autor = new Autor($linha['nome'], $linha['nacionalidade']); $autor->identificar((int)$linha['id']); return $autor;
    }
}
final class LivroRepository extends AbstractRepository {
    protected function tabela(): string { return 'livros'; }
    protected function dados(object $objeto): array {
        if (!$objeto instanceof Livro) throw new InvalidArgumentException('Esperado Livro');
        return ['titulo'=>$objeto->titulo(), 'isbn'=>$objeto->isbn(), 'ano_publicacao'=>$objeto->ano()];
    }
    protected function hidratar(array $linha): Livro {
        $livro = new Livro($linha['titulo'], $linha['isbn'], $linha['ano_publicacao'] === null ? null : (int)$linha['ano_publicacao']);
        $livro->identificar((int)$linha['id']);
        $rows = $this->consulta('SELECT a.* FROM autores a JOIN livros_autores la ON a.id = la.autor_id WHERE la.livro_id = ? ORDER BY a.id', [$livro->id()])->fetchAll();
        foreach ($rows as $row) { $autor = new Autor($row['nome'], $row['nacionalidade']); $autor->identificar((int)$row['id']); $livro->adicionarAutor($autor); }
        return $livro;
    }
    public function salvarComAutores(Livro $livro, array $ids): int {
        $this->pdo->beginTransaction();
        try {
            // A identidade do livro original so e atribuida depois do commit.
            $copia = clone $livro;
            $id = $this->salvar($copia);
            $this->consulta('DELETE FROM livros_autores WHERE livro_id = ?', [$id]);
            foreach (array_unique($ids) as $autorId) $this->consulta('INSERT INTO livros_autores (livro_id, autor_id) VALUES (?, ?)', [$id, $autorId]);
            $this->pdo->commit(); $livro->identificar($id); return $id;
        } catch (Throwable $e) { if ($this->pdo->inTransaction()) $this->pdo->rollBack(); throw $e; }
    }
    public function desvincularAutores(int $id): void { $this->consulta('DELETE FROM livros_autores WHERE livro_id = ?', [$id]); }
}
