# Release Notes

## v3.0.0 (2018-08-21)

### Added

- `formatXML` movido para a *trait* `FormatarXML` [(6e7f11d)](https://github.com/crphp/webservice/commit/6e7f11d03cab8d0dafbee24ed210fb600a964f05)

### Changed

- Criado método `xmlToArray` e adequações ao padrão **PHPDoc** [(2cb1745)](https://github.com/crphp/webservice/commit/2cb1745f6f7cde49119ead914be39cb9f4e86c81)
- Reescrita de partes do código para utilização de uma interface comum entre as classes [(00bbcf7)](https://github.com/crphp/webservice/commit/00bbcf7ae8cac4c0f95389a355db39a157168b8c)
- `setWsdl` aceita incremento de cabeçalho [(94617ae)](https://github.com/crphp/webservice/commit/94617ae0c69ec11729670032874fca1df7449aa1)
- Adequação de nome de variável e estilo ap padrão **PSR-2** [(691cccb)](https://github.com/crphp/webservice/commit/691cccb3f5c0196f3a288881eefb4b03d43f1d0b)

### Fixed

- Correção na documentação [(b6427df)](https://github.com/crphp/webservice/commit/b6427df545e5c412803ad2b8ed61504a8ca6e618)

## v2.1.0 (2018-08-14)

### Added

- Add suporte a interpretação de XML literal pelo método `doRequest` [(01e9b93)](https://github.com/crphp/webservice/commit/01e9b93e724f3f8d53a1e20e03efb7c3495e201f)

### Changed

- Documentação adequada ao padrão do phpdoc [(76fe08e)](https://github.com/crphp/webservice/commit/76fe08e48cbc264c6001c72366c7b1be89ecaa3b)
- `setRequest` aceita incremento de cabeçalho [(f6b8396)](https://github.com/crphp/webservice/commit/f6b8396a9459a55b9639e820c6cf5d2fa7a6a592)
- `ClienteGenerico` agora suporta fluent interface [(e420e85)](https://github.com/crphp/webservice/commit/e420e8561dc74e6747758e2355ece598f9154267)
- `getResponseHeader` melhorado para permite saída formatada com quebra de linha [(acf053c)](https://github.com/crphp/webservice/commit/acf053cb0f0df411dc61aec9e3a32a40b487d1dd)
- Ajustes na documentação, phpdoc e inclusão do `CHANGELOG` [(103954b)](https://github.com/crphp/webservice/commit/103954b89e16d59d16784d383c68ac31cf52f25a)

## v2.0.0 (2018-08-10)

### Changed

- Ajustes no README.md e alterações gerais no arquivo `/src/Soap` [(c5f2d34)](https://github.com/crphp/webservice/commit/c5f2d34285a44b9fbc7213f344c1ebe4bfae8ec1)
- Add **ext-curl**, **ext-soap** e **ext-dom** ao `composer.json` [(9f1160c)](https://github.com/crphp/webservice/commit/9f1160cf9bd2f4f449a526fc742cba9f1f52847e)
- Adequação do phpdoc no arquivo `/src/ClienteGenerico` [(4ec65e6)](https://github.com/crphp/webservice/commit/4ec65e62f82ec4583e9487444311ce2ff878113c)
- Add `location` ao método `setWSDL` e adequação de variáveis [(0e4a0f1)](https://github.com/crphp/webservice/commit/0e4a0f16fa3a3acb8b3e169635620c5b8923dde6)
- Bug no método `consult` (`/src/Soap.php`) corrigido [(7d8f19d)](https://github.com/crphp/webservice/commit/7d8f19d7d404613570daf3dc2c7c9f1c55e8a509)
- Pequeno ajuste no `README.md` [(3d5134b)](https://github.com/crphp/webservice/commit/3d5134b47d6aba4b45e0516be97da63c9add0fb1)
- Métodos e variáveis alteradas de português para inglês [(576d937)](https://github.com/crphp/webservice/commit/576d937bb611b7925173c6ead5a9ebcb4f3486fe)

## v1.0.1 (2018-08-02)

### Fixed

- Corrigida validação de status http [(78f8cf3)](https://github.com/crphp/webservice/commit/78f8cf35b9a2dee581cd309518b44b8b008731ea)

## v1.0.1 (2016-11-28)

### Changed

- Pequenas adequações de código e documentação [(54b37a5)](https://github.com/crphp/webservice/commit/54b37a598499c5d0c896faff377d9c227cc0dce6)

## v1.0.0 (2016-11-18)

### Added

- Primeira versão estável disponibilizada [(b3ecd0f)](https://github.com/crphp/webservice/commit/b3ecd0f85a119eded09a5a834af5be359903d68b)