<?php
declare(strict_types=1);
abstract class Entidade {
    private ?int $id = null;
    public function id(): ?int { return $this->id; }
    public function identificar(int $id): void {
        if ($id < 1 || ($this->id !== null && $this->id !== $id)) throw new LogicException('Identidade invalida');
        $this->id = $id;
    }
}
final class Autor extends Entidade {
    public function __construct(private string $nome, private ?string $nacionalidade = null) {
        $this->nome = trim($nome);
        if ($this->nome === '') throw new InvalidArgumentException('Informe o nome');
    }
    public function nome(): string { return $this->nome; }
    public function nacionalidade(): ?string { return $this->nacionalidade; }
}
final class Livro extends Entidade {
    private array $autores = [];
    public function __construct(private string $titulo, private ?string $isbn = null, private ?int $ano = null) {
        $this->alterarTitulo($titulo);
        if ($ano !== null && ($ano < 1 || $ano > (int)date('Y'))) throw new InvalidArgumentException('Ano invalido');
        if ($isbn !== null && !preg_match('/^\d{13}$/', $isbn)) throw new InvalidArgumentException('ISBN deve conter 13 digitos');
    }
    public function alterarTitulo(string $titulo): void {
        if (trim($titulo) === '') throw new InvalidArgumentException('Informe o titulo');
        $this->titulo = trim($titulo);
    }
    public function titulo(): string { return $this->titulo; }
    public function isbn(): ?string { return $this->isbn; }
    public function ano(): ?int { return $this->ano; }
    public function autores(): array { return array_values($this->autores); }
    public function adicionarAutor(Autor $autor): void {
        $chave = $autor->id() === null ? 'novo:'.spl_object_id($autor) : 'id:'.$autor->id();
        $this->autores[$chave] = $autor;
    }
}
