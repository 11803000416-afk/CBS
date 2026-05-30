#!/usr/bin/env bash
set -euo pipefail

BASE_DIR="$(pwd)"
OUT_DIR="$BASE_DIR/public/images/brands"
mkdir -p "$OUT_DIR"

brands=(
  "Maruti Suzuki" "Tata" "Mahindra" "Hyundai" "Toyota" "Kia"
  "BMW" "Skoda" "Honda" "MG" "Volkswagen" "Renault"
  "Mercedes-Benz" "Land Rover" "Nissan" "BYD" "Citroen" "VinFast"
  "Jeep" "Audi" "Porsche" "Volvo" "Lexus" "Fiat"
  "Lamborghini" "Mini" "Force Motors" "Jaguar" "Ferrari" "JSW"
)

# Create slug similar to simple lowercase + non-alnum replaced by '-'
slugify() {
  echo "$1" | iconv -f utf8 -t ascii//TRANSLIT 2>/dev/null | tr '[:upper:]' '[:lower:]' | sed -E 's/[^a-z0-9]+/-/g' | sed -E 's/^-|-$//g'
}

for brand in "${brands[@]}"; do
  slug=$(slugify "$brand")
  out="$OUT_DIR/${slug}.svg"

  echo "Processing: $brand -> $slug"

  # Try jsDelivr simple-icons
  urls=(
    "https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/${slug}.svg"
    "https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/$(echo $slug | sed 's/-//g').svg"
  )

  saved=0
  for url in "${urls[@]}"; do
    echo -n "  Trying $url ... "
    if curl -fsSL -o "$out" "$url"; then
      echo "saved"
      saved=1
      break
    else
      echo "no"
    fi
  done

  if [ "$saved" -eq 0 ]; then
    # Fallback: try Wikimedia commons by brand words (simple attempts)
    wikifilenames=(
      "${slug}.svg"
      "${slug}_logo.svg"
      "${slug%*-}.svg"
    )
    for w in "${wikifilenames[@]}"; do
      wurl="https://upload.wikimedia.org/wikipedia/commons/$w"
      echo -n "  Trying Wikimedia $wurl ... "
      if curl -fsSL -o "$out" "$wurl"; then
        echo "saved"
        saved=1
        break
      else
        echo "no"
      fi
    done
  fi

  if [ "$saved" -eq 0 ]; then
    echo "  Could not fetch logo for $brand, leaving placeholder"
    rm -f "$out" 2>/dev/null || true
  fi
done

echo "Done. Logos saved to $OUT_DIR (existing ones overwritten)." 
