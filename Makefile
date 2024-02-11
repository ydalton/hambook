SRC_DIR	:=	src
TARGET	:=	dist
PHP_DIR	:=	php
START	:=	start.sh
BAT		:=	start.bat
LOG_DIR	:=	logbook
APK		:=	apk.static

win32: $(TARGET) $(BAT) win32php
	rm -rf $(LOG_DIR)
	mkdir $(LOG_DIR)
	mkdir $(LOG_DIR)/storage
	echo "[{\"pk_counter\": 1}]" > $(LOG_DIR)/storage/log.json
	cp -r $(TARGET) $(BAT) $(PHP_DIR) $(LOG_DIR)
	rm -f logbook-win32.zip
	zip -r logbook-win32.zip $(LOG_DIR)

win32php:
	wget https://windows.php.net/downloads/releases/php-8.3.2-nts-Win32-vs16-x64.zip
	unzip php-8.3.2-nts-Win32-vs16-x64.zip -d $(PHP_DIR)

pkg: $(TARGET) $(START) php
	rm -rf $(LOG_DIR)
	mkdir $(LOG_DIR)
	mkdir $(LOG_DIR)/storage
	echo "[{\"pk_counter\": 1}]" > $(LOG_DIR)/storage/log.json
	cp -r $(TARGET) $(PHP_DIR) $(LOG_DIR)
	sed 's/$(SRC_DIR)/$(TARGET)/g' $(START) > $(LOG_DIR)/$(START)
	chmod +x $(LOG_DIR)/$(START)
	tar -czf $(LOG_DIR)-$(shell uname | tr '[A-Z]' '[a-z]')-$(shell arch).tar.gz $(LOG_DIR)
	rm -rf $(LOG_DIR)

$(TARGET): $(SRC_DIR)/
	rm -rf $(TARGET)
	mkdir -p $(TARGET)/css
	@cp -v $(SRC_DIR)/*.php $(TARGET)/
	@cp -vr $(SRC_DIR)/assets $(TARGET)/
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
	rm -rf $(TARGET) *.zip *.tar.gz $(APK) $(LOG_DIR) $(PHP_DIR) node_modules package-lock.json

.PHONY: $(TARGET) php
