TARGET	:=	dist

$(TARGET): src/
	rm -rf dist
	mkdir -p dist/css
	@cp -v src/*.php dist/
	@cp -vr src/assets dist/
	@cp -v src/log.json dist/
	npx tailwindcss -i src/style.css -o dist/css/style.css

.PHONY: $(TARGET)
