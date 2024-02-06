SRC_DIR	:=	src
TARGET	:=	dist
PHP_DIR	:=	php
START	:=	start.sh
LOG_DIR	:=	logbook
TARBALL	:=	$(LOG_DIR)-$(shell arch).tar.gz
APK	:=	apk.static

pkg: $(TARGET) $(START) php
	rm -rf $(LOG_DIR)
	mkdir $(LOG_DIR)
	cp -r $(TARGET) $(PHP_DIR) $(LOG_DIR)
	sed 's/$(SRC_DIR)/$(TARGET)/g' $(START) > $(LOG_DIR)/$(START)
	chmod +x $(LOG_DIR)/$(START)
	tar -czf $(TARBALL) $(LOG_DIR)

$(TARGET): $(SRC_DIR)/
	rm -rf $(TARGET)
	mkdir -p $(TARGET)/css
	@cp -v $(SRC_DIR)/*.php $(TARGET)/
	@cp -vr $(SRC_DIR)/assets $(TARGET)/
ifneq (,$(wildcard $(SRC_DIR)/log.json))
	@cp -v $(SRC_DIR)/log.json $(TARGET)/
else
	echo "[]" > $(TARGET)/log.json
endif
	npm install
	npx tailwindcss -i $(SRC_DIR)/style.css -o $(TARGET)/css/style.css --minify

php:
	rm -rf $(PHP_DIR)
ifeq (,$(wildcard ./$(APK)))
	wget https://gitlab.alpinelinux.org/api/v4/projects/5/packages/generic/v2.14.0/$(shell arch)/$(APK)
	chmod +x ./$(APK)
endif
	unshare -U --map-user=0 --map-group=0 ./$(APK) --arch $(shell arch) -X "http://dl-cdn.alpinelinux.org/alpine/latest-stable/main" -X "http://dl-cdn.alpinelinux.org/alpine/latest-stable/community" -U --allow-untrusted --root $(PHP_DIR) --initdb add php busybox
	mkdir -p $(PHP_DIR)/app
	rm -r $(PHP_DIR)/var/cache

clean:
	rm -rf $(TARGET) $(TARBALL) $(APK) $(LOG_DIR) $(PHP_DIR) node_modules package-lock.json

.PHONY: $(TARGET) php
