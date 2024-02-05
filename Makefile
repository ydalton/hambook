dist: src/
	rm -rf dist
	mkdir -p dist/css
	cp src/*.php dist/
	npx tailwindcss -i src/style.css -o dist/css/style.css
