
**CocoaPods** - простой и удобный менеджер зависимостей в Objective-C проектах
=====


####1. Введение или зачем это все нужно?

С каждым месяцем становится все больше разнообразных библиотек программного кода - от специализированных до универсальных. 
Исходные коды хранятся в git, hg, svn итд.
Все они регулярно обновляются и зависят друг от друга. 
Пару лет назад приходилось качать исходные коды с сайтов разработчиков и интегрировать их в свой проект. С появлением и распространение github и bitbucket мы стали использовать их возможности и мощь — вместо изобретения своих велосипедов мы начали брать open-source проекты и активно контрибьютить в них.
Вся эта экосистема сложилась таким образом, что с одной стороны мы имеем:
- регулярно обновляющиеся библиотеки open-source кода хранящиеся, например в git
- свои собственные библиотеки в своих собственных репозиториях (git, hg, svn), которые по тем или иным причинам мы не можем или не хотим выпускать в паблик
а с другой разработчика, который хочет использовать все это в своем проекте, чтобы не изобретать велосипеды и иметь возможность просто и прозрачно апдейтить/добавлять.

####2. Обзор и Getting Started

У .NET сообщества есть NuGet, у Ruby - RubyGems, логично что у молодого и активно развивающегося сообщества iOS девелоперов рано или поздно должна был появиться похожий инструмент. Первым, но от этого не менее привлекательным и удобным в использовании стал продукт CocoaPods. CocoaPods - это не просто менеджер пакетов, но и целая экосистема, построенная вокруг third-party библиотек кода. От разработчика требуется только описать зависимости в текстовом файле, а остальное CocoaPods сделает сам - разрешит зависимости, загрузит и обновит библиотеки, сконфигурирует ваш файл проекта.


#####2.1. Установка
	
CocoaPods распространяется как Ruby gem, устанавливается парой команд в терминале:

	$ [sudo] gem install cocoapods
	$ pod setup

	$ pod list
	$ pod search af
	$ pod --help

#####2.2. Podfile
	
Как я уже говорил, разработчику всего лишь нужно описать зависимости к своему проекту в текстовом файле. Podfile содержит список зависимостей вашего приложения и обычно располагается в корне проекта. Пример простого файла (live):

	platform :ios

	dependency 'SSToolkit'
	dependency 'AFNetworking', '>= 0.5.1'
	dependency 'CocoaLumberjack'
	dependency 'Objection'

Есть возможность указать версию deployment target:

	platform :ios, '4.0'
В iOS для target **<4.3** в ARCHS будет добавлена архитектура **armv6**.

Кроме того, есть возможность указать несколько таргетов:

	dependency 'RestKit'

	target :debug do
	    dependency 'CocoaLumberjack'
	end

	target :test, :exclusive => true do
	    dependency 'Kiwi'
	end

Полную документацию можно посмотреть в [Podfile docs](http://rubydoc.info/gems/cocoapods/Pod/Podfile).


#####2.3. Внедрение в проект

Собственно, теперь работа разработчика закончилась - остальное CocoaPods сделает сам.

	$ pod install ProjectName.xcodeproj

CocoaPods cоздаст дополнительный проект со всеми указанными зависимостями, которые будут собираться в статическую библиотеку, которая будет прилинкована к вашему проекту. Эти проекты помещаются в workspace (аналог solutions в .NET или worcspace в Eclipse), который и нужно будет запускать. 

Обновлять проект нужно командой 
	
	$ pod install

######Какие изменения будут внесены в проект:

1. Создается или обновляется workspace
2. Ваш проект добавляется в workspace
3. Проект с зависимостями, создающий статическую библиотеку добавляется в workspace
4. Добавляется .xcconfig файл в ваш проект
5. Создается зависимость от статической библиотеки в вашем проекте
6. Добавляется build phase, где запускается скрипт, копирующий ресурсы


#####2.4. Версии

Мы можем управлять тем, какая версия библиотеки будет использоваться.
	
	spec.dependency 'CocoaLumberjack', '~> 1.0.7'

Правила [указания версий](http://docs.rubygems.org/read/chapter/7) и [спецификаторы](http://docs.rubygems.org/read/chapter/16#page74) основаны на правилах RubyGems.


####3. Спецификации

Каждый проект представлен спецификацией в которой указывается что/чей/откуда он.

#####3.1. Пример простого файла

Pod::Spec.new do |s|
  s.name     = 'SVProgressHUD_HEAD'
  s.version  = '0.6'
  s.platform = :ios
  s.license  = 'MIT'
  s.summary  = 'A clean and lightweight progress HUD for your iOS app.'
  s.homepage = 'http://samvermette.com/199'
  s.author   = { 'Sam Vermette' => 'hello@samvermette.com' }
  s.source   = { :git => 'https://github.com/samvermette/SVProgressHUD.git', :tag => '0.6' }

  s.description = 'SVProgressHUD is an easy-to-use, clean and lightweight progress HUD for iOS. ' \
                  'It’s a simplified and prettified alternative to the popular MBProgressHUD. '  \
                  'Its fade in/out animations are highly inspired on Lauren Britcher’s HUD in '  \
                  'Tweetie for iOS. The success and error icons are from Glyphish.'
  s.source_files = 'SVProgressHUD/*.{h,m}'
  s.framework    = 'QuartzCore'
  s.resources    = 'SVProgressHUD/SVProgressHUD.bundle'
end

В спецификациях указана вся необходимая информация, информация о сабспецификациях, необходимость ARC и зависимость от фреймворков итд. [Полная документация](https://github.com/CocoaPods/CocoaPods/wiki/The-podspec-format).

	$ pod spec create Peanut
	$ edit Peanut.podspec
	$ pod spec lint Peanut.podspec

#####3.2. Репозитории спецификаций

Каждая спецификация представлена .podspec файлом, некоторые разработчики уже включают их в свои проекты. CocoaPods [создала](https://github.com/CocoaPods/Specs) файлы спецификаций для популярных проектов и мы можем (и желательно) ими пользоваться. Они лежат в открытом доступе на github и мы можем делать пулреквесты своих проектов. Мы также можем создать свои ЛОКАЛЬНЫЕ .podfile и даже свой локальный репозиторий (например, внутри компании).

#####3.3. Podfile'ы и версии

Следует понимать, что используя спецификации CocoaPods, мы, чаще всего, не ссылаемся на самую последнюю ревизию, а ссылаемся на tag или commit (в терминах git). Это нужно иметь ввиду. Кто-то, вроде Федя, спрашивал об этом. Но мы всегда можем создать свой podspec файл, в котором будем ссылаться на HEAD или на конкретный commmit. Просто иметь ввиду.

####4. Живая презентация
	
####5. Ссылки

* http://cocoapods.org/
* https://github.com/CocoaPods/CocoaPods
* https://github.com/CocoaPods/Specs
* http://rubydoc.info/gems/cocoapods/