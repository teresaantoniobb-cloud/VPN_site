#!/usr/bin/env bash
set -euo pipefail

echo "Starting scripts/unzip-and-run.sh"

# Exemplo: lista arquivos
echo "Arquivos no repositório:"
ls -la

# Se houver um zip específico no root, descompacta para extracted/specific
ZIPFILE="site-files.zip"
if [ -f "$ZIPFILE" ]; then
  echo "Encontrado $ZIPFILE — descompactando para extracted/specific"
  mkdir -p extracted/specific
  unzip -o "$ZIPFILE" -d extracted/specific
else
  echo "$ZIPFILE não encontrado — pulando descompactação específica"
fi

# Coloque aqui os comandos adicionais que você precisa executar
# exemplo: gerar arquivos estáticos, mover, etc.
echo "Script concluído."