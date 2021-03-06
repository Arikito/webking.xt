<?if(G::IsAgent()){?>
	<div class="orders_history">
		<h2>Условия соглашения</h2>
		<div class="license_agreement_main">
			<?=$GLOBALS['CONFIG']['agent_license_agreement'];?>
		</div>
	</div>
<?}else{?>
	<a class="bonus_detalies" href="<?=Link::Custom('page', 'Stat_torgovym_agentom');?>" class="details">
		<i class="material-icons">help_outline</i> Детали агентской программы
	</a>
	<div class="license_agreement">
		<h2>Условия соглашения сотрудничества</h2>
		<div class="license_agreement_descr">
			<p class="section">1. ПРЕДМЕТ СОГЛАШЕНИЯ И ВСТУПЛЕНИЕ В СИЛУ</p>
			<p>1.1. Настоящее Соглашение регулирует отношения между Агентом, с одной стороны, и Администрацией сайта xt.ua (далее - Администрация), с другой стороны, по предоставлению Агенту права доступа к www.xt.ua (далее - Портал) и право пользования инструментами Портала, согласно  учетной записи при регистрации.</p>
			<p>1.2. Настоящее Соглашение вступает в силу с момента  нажатия пользователем  кнопки, завершающей процедуру регистрации и позволяющей Пользователю приступить к использованию  сервиса стать агентом  в кабинете пользователя. подтверждение получения статуса Агента настоящей оферты происходит путем выражения Пользователем согласия с агентским соглашениемт на сайте  xt.ua и присвоение агенту уникального номера- промокода.</p>

			<p class="section">2. ИСПОЛЬЗУЕМЫЕ ТЕРМИНЫ</p>
			<p><span class="underline">Портал</span> - программный комплекс, размещенный в сети интернет по адресу www.xt.ua  и предоставляющий Пользователям WEB-интерфейс с функциональными возможностями, различными для каждой из учетных записей, а также Посетителей портала.</p>
			<p><span class="underline">Посетитель портала или Гость</span> - человек, просматривающий страницы Портала и не имеющий Учетной записи (аккаунта) или в текущее время не авторизованный на Портале. Посетитель может просматривать страницы Портала.</p>
			<p><span class="underline">Пользователь</span> - физическое лицо, добровольно прошедшее регистрацию на Портале. Пользователем может быть дееспособное совершеннолетнее лицо, либо несовершеннолетний, достигший 16 лет и объявленный полностью дееспособным (эмансипированным) в порядке, предусмотренном действующим законодательством Украины.</p>
			<p><span class="underline">Агент</span> - физическое лицо, добровольно прошедшее регистрацию на Портале и принявшее агентское соглашение для дальнейшего распространения информации о портале с целью привлечения пользователей и посетителей для совершения покупок, получающие материальное вознаграждение от посетителей и пользователей. Агент является одной из сторон настоящего Соглашения.</p>
			<p><span class="underline">Пользователь агента</span> - физическое лицо, добровольно прошедшее регистрацию на Портале имеющее своего агента (куратора), который при оформлении заказов вводит промокод Агента, чем привязывает себя к данному Агенту.</p>
			<p><span class="underline">Промокод</span> - уникальный номер, который, присваивается агенту при принятии Агентского соглашения.  Номер, помогающий идентифицировать поток посетителей и пользователей привлеченных агентом.</p>
			<p><span class="underline">Регистрация</span> - процедура, в ходе которой Пользователь предоставляет достоверные данные о себе по утвержденной Администрацией форме, а также Логин и Пароль. Регистрация считается завершенной только в случае успешного прохождения Пользователем всех ее этапов, Нажатие пользователем кнопки «ПОДТВЕРДИТЬ»  является моментом заключения настоящего Соглашения между Пользователем и Администрацией Портала, т.е. полного и безоговорочного согласия Сторон с условиями настоящего Соглашения.</p>
			<p><span class="underline">Логин</span> - уникальное имя (псевдоним) Пользователя, указанный им при Регистрации с целью использования для идентификации Пользователя и используемый в сочетании с Паролем для получения доступа Пользователя к сервисам Портала.</p>
			<p><span class="underline">Пароль</span> - буквенно-цифровой код, указанный Пользователем при Регистрации, хранимый обеими сторонами настоящего Соглашения в тайне от третьих лиц и используемый в сочетании с Логином для получения доступа Пользователя к сервисам Портала.Логин и пароль, введенные Пользователем, признаются Сторонами аналогом собственноручной подписи Пользователя.</p>
			<p><span class="underline">Персональные регистрационные данные</span> - данные, добровольно указанные Пользователем при прохождении Регистрации. Данные хранятся в базе данных Портала и подлежат использованию исключительно в соответствии с настоящим Соглашением и действующим законодательством Украины.</p>
			<p><span class="underline">Оферта</span> - это предложение, сделанное с целью заключить договор. Т.е. оферта всегда предшествует заключению договора.</p>

			<p class="section">3. ПРАВА И ОБЯЗАННОСТИ СТОРОН</p>
			<p>3.1. Права и обязанности Администрации.</p>
			<p>3.1.1. Администрация вправе предоставить Агенту доступ к Порталу и поддерживать Портал и инструменты в рабочем состоянии</p>
			<p>3.1.2. Администрация оставляет за собой право сопровождать деятельность Агента в рамках использования им Портала и предотвращать публикацию любых Материалов, несущих угрозу нормальному функционированию портала.
				Администрация имеет право отказать Агенту в размещении информации любого вида без предупреждения и без объяснения причин такого отказа.
				Администрация может на свое усмотрение в любой момент удалить размещаемую Агентами информацию без предупреждения и без объяснения причин такого удаления</p>
			<p>3.1.3. Агент соглашается с тем, что Администрация может использовать (обрабатывать и т.п.) его персональные данные, указанные Агентом при регистрации, в целях проведения мероприятий, связанных с Порталом, а также направлять на предоставленный Агентом электронный адрес и размещать в пространстве, ограниченном доступом Агента, рекламные и информационные сообщения по своему усмотрению.
				Администрация Портала имеет право сохранять следующую информацию об использовании Агентами (Посетителями) Портала: частоту посещаемости страниц, активность, дату последней авторизации, посещаемость разделов каталога товаров и прочее. Администрация Портала гарантирует, что подобная информация не будет передана третьим лицам, кроме случаев, прямо предусмотренных действующим законодательством Украины, и будет использована исключительно в статистических целях, которые позволят оценить качество сервиса и, возможно, улучшить или усовершенствовать его.
				Администрация Портала обеспечивает сохранность Вашей конфиденциальной информации.
				Администрация Портала оставляет за собой право передавать информацию об использовании Портала в обобщенном виде (не персонифицированную, и не привязанную к учетным записям Пользователей) третьим лицам в целях отображения статистики работы Портала, а также при проведении маркетинговых или других исследованиях.
				Администрация не несет ответственности за разглашение Агентом своей конфиденциальной информации, а также, если такая информация стала известной из-за халатности, или в виду других обстоятельств, и не обязана возмещать моральный и/или материальный ущерб, понесенный Пользователем в случае её разглашения.</p>
			<p>3.1.4. Администрация обязуется осуществлять техническую поддержку Агента по вопросам и в порядке, как указано ниже.</p>
			<p>3.1.5. Техническая поддержка в форме письменной или устной телефонной консультации предоставляется только по официальному запросу Агента, направленному в службу поддержки Портала www.xt.ua Консультация специалиста может быть предоставлена по следующим вопросам: регистрация и проблемы при ее прохождении, функционирование Портала и его инструментов, восстановление утраченного пароля доступа. Не предоставляются консультации по вопросам настройки оборудования, программного обеспечения или Интернет-доступа Агента или иных третьих лиц, а также по другим вопросам, не имеющим отношения к работе Портала.</p>
			<p>3.1.6. Администратор не обязуется возвращать или уничтожать Материалы, предоставленные Агентом в связи или при пользовании Портала.</p>
			<p>3.1.7. Администрация обязуется сообщать Агенту о нововведениях и дополнительных функциях Портала. О проведении возможных акций и обновлений www.xt.ua.</p>
			<p>3.2. Права и обязанности Посетителя.</p>
			<p>3.2.1. Посетитель обязуется не пользоваться доступом к содержимому Портала иными способами, кроме как через веб-интерфейс Портала или через встроенный флеш-плеер.</p>
			<p>3.2.2.  Посетитель использует функции сервисы Портала по назначению.</p>
			<p>3.2.3. Посетитель не размещает неправдивую, вредоносную информацию способную нанести вред работе портала.</p>
			<p>3.2.4. Посетитель имеет  право сохранять свою конфиденциальность при  работе  с порталом (не регистрироваться)</p>
			<p>3.2.5.Посетитель имеет право получать консультации по работе с порталом в полном объеме по своему желанию.</p>
			<p>3.3. Права и обязанности Пользователя</p>
			<p>3.3.1. Пользователь имеет право использовать Портал не запрещенными настоящим Соглашением и действующим законодательством Украины способами.</p>
			<p>3.3.2. Пользователь принимает на себя полную ответственность за содержимое загруженной им информации, за нарушение прав интеллектуальной собственности (авторских прав) и других прав третьих лиц, за использование товарных знаков, логотипов и брендов, несогласованное с их владельцами, а также за какие-либо иные нарушения прав и интересов третьих лиц, осуществленные размещением Пользователем информации на Портале.
				Администрация не несет ответственности за содержимое файлов и информации, за несоответствие информации требованиям действующего законодательства Украины, а также за какие-либо иные нарушения прав и интересов третьих лиц, осуществленные размещением Пользователем информации на Портале, а также отосланной при помощи внутренней системы сообщений Портала. В случае если Администрации поступят какие-либо претензии от третьих лиц, связанные с содержимым информации или сообщений, урегулирование конфликта и разногласий полностью возлагается на Пользователя, загрузившего эту информацию или отославшего эти сообщения.</p>
			<p>3.3.3. В случае возникновения в работе Портала проблем технического характера, а также в случае получения Пользователем сообщений, являющихся несанкционированной рекламной рассылкой либо содержащих запрещенные настоящим Соглашением Материалы, в том числе угрозы или файлы с подозрением на вирус, в случае если Пользователь обнаруживает факты, дающие основания полагать, что его доступ был использован кем-либо не санкционированно, Пользователь имеет право обратиться к Администрации для выяснения ситуации и принятия необходимых мер.</p>
			<p>3.3.4. Пользователь ответственен за хранение пароля/логина вне доступа третьих лиц и своевременную их смену в случае утери или иной необходимости.</p>
			<p>3.4. Права и обязанности Агента.</p>
			<p>3.4.1. Права и обязанности Агента включают в себя вышеперечисленные права и обязанности посетителя и пользователя портала xt.ua.</p>
			<p>3.4.2. Агент имеет право использовать все возможности и ресурсы уголка агента, предлагаемые порталом для привлечения пользователей и посетителей с целью получения бонусов от заказов его пользователей и посетителей.</p>
			<p>3.4.3. Агент имеет право распространять сертификаты со своим промокодом в любом количестве и в местах выбранных на свое усмотрение.</p>
			<p>3.4.4. Посетителями агента считаются пользователи и посетители, которые при оформлении заказа указали промокод агента.</p>
			<p>3.4.5. Агент получает вознаграждение от заказов со своим промокодом согласно следующего правила:
				-5% от сумм розничных заказов, то есть при сумме покупки от 1 грн. до <?php echo $GLOBALS['CONFIG']['retail_order_margin'] ?> грн.
				-2% от сумм оптовых покупок, то есть при сумме покупки от <?php echo $GLOBALS['CONFIG']['retail_order_margin'] ?> грн. до <?php echo $GLOBALS['CONFIG']['wholesale_order_margin'] ?> грн.
				-1,5% от дилерских покупок, то есть при сумме покупки от <?php echo $GLOBALS['CONFIG']['wholesale_order_margin'] ?> грн. до <?php echo $GLOBALS['CONFIG']['full_wholesale_order_margin'] ?> грн.
				-0,5% от партнерских покупок, то есть при сумме покупки от <?php echo $GLOBALS['CONFIG']['full_wholesale_order_margin'] ?> грн. до 100000 грн.</p>

			<p class="section">4. ОТВЕТСТВЕННОСТЬ СТОРОН</p>
			<p>4.1. Ответственность Администрации.</p>
			<p>4.1.1. Администрация обязуется обеспечить конфиденциальность и сохранность персональных данных Пользователя от третьих лиц кроме случаев, когда такое разглашение произошло по не зависящим от Администрации причинам, а также за исключением случаев, предусмотренных действующим законодательством Украины.</p>
			<p>4.1.2. Администрация обязуется обеспечить стабильную работу Портала, постепенное его совершенствование, максимально быстрое исправление ошибок в работе Портала, однако:
				Портал предоставляется Пользователю по принципу «как есть». Это означает, что Администрация:
				не гарантирует отсутствие ошибок в работе Портала;
				не несет ответственность за его бесперебойную работу, их совместимость с программным обеспечением и техническими средствами Пользователя и иных лиц;
				не несет ответственность за потерю Материалов или за причинение любых убытков, которые возникли или могут возникнуть в связи, с или при пользовании Порталом;
				не несет ответственности, связанной с любым искажением, изменением, оптической иллюзией изображений, фото- видео- и иных Материалов Пользователя, которое может произойти или производится при использовании Портала, даже если это вызовет насмешки, скандал, осуждение или пренебрежение;
				не несет ответственность за неисполнение либо ненадлежащее исполнение своих обязательств вследствие сбоев в телекоммуникационных и энергетических сетях, действий вредоносных программ, а также недобросовестных действий третьих лиц, направленных на несанкционированный доступ и/или выведение из строя программного и/или аппаратного комплекса Портала.</p>
			<p>4.1.3. Администрация не при каких обстоятельствах не несет ответственности за содержание опубликованных, отправленных Пользователем или полученных им от других Пользователей Материалов и сообщений. Также Администрация не несет ответственности за результаты Договоров, Сделок купли-продажи, или любых других отношений между пользователями, совершенных при помощи сервисов Портала.</p>
			<p>4.1.4. Администрация не обязуется контролировать содержание Материалов и ни при каких обстоятельствах не несет ответственность за соответствие их требованиям действующего законодательства Украины, а также за возможное нарушение прав третьих лиц в связи с размещением Материалов при или в связи с использованием Портала.</p>
			<p>4.2. Ответственность Агента</p>
			<p>4.2.1. Агент  несет ответственность за предоставление достоверной информации о себе при Регистрации.</p>
			<p>4.2.2. Агент соглашается никогда и ни при каких обстоятельствах не использовать Портал для:</p>
			<p>1) публикации, распространения, хранения, передачи в любой форме (например, но не ограничиваясь, в форме текстового сообщения, вложенного файла любого формата, ссылки на размещение в сети) Материалов, которые:
				носят непристойный, оскорбительный, вульгарный, вредоносный, угрожающий, клеветнический, деликтный, ложный или порнографический характер;
				оскорбляют честь и достоинство, права и законные интересы третьих лиц, способствуют разжиганию религиозной, расовой, этнической или межнациональной розни, содержат элементы насилия, призывают к нарушению действующего законодательства и противоправным действиям и т.п.;
				нарушают права на результаты интеллектуальной деятельности и на средства индивидуализации (в том числе авторские, смежные, патентные и т.д.) третьих лиц;
				нарушают права несовершеннолетних лиц;
				способствуют возникновению интереса к или распространению наркотиков, оружия и боеприпасов, любой форме террористической, противоправной и нацистской деятельности;
				содержат не разрешенную к разглашению информацию (информацию, составляющую государственную тайну, персональные данные третьих лиц, информацию, запрещенную к разглашению в силу договорных или фидуциарных отношений Пользователя и т.п.);
				направлены против других Пользователей;
				содержат программные вирусы или иные компьютерные коды, программы, файлы, направленные на нарушение функциональности любого компьютерного или телекоммуникационного оборудования, их частей, в том числе серверов и прочих компонентов сетевой инфраструктуры и программного обеспечения. Пересылка вредоносных программ запрещена в любом виде, в том числе в виде полного программного кода, либо его части, отдельных файлов любых форматов, а также ссылок на их размещение в сети;
				содержат несанкционированную с Администратором рекламную информацию, спам, флуд, «письма счастья», схемы многоуровневого маркетинга, способы заработка в Интернет (в том числе с использованием e-mail), информацию, провоцирующую «цепную реакцию» в рассылке сообщений получателями и другую аналогичную информацию, имеют в заголовке более половины заглавных букв, содержат ненормативную лексику, содержат орфографические ошибки в словах;
				материалы, которые намеренным или случайным образом нарушают законодательство Украины.</p>
			<p>2) подключения и использования любого программного обеспечения, предназначенного для взлома или агрегации личных данных других Пользователей, включая логины, пароли и т.д., а также для проведения автоматической массовой рассылки какого бы то ни было содержания.</p>
			<p>3) для введения кого-либо в заблуждение путем присвоения себе чужого имени и намеренной публикации, отправки сообщений или другого способа использования присвоенного имени противозаконно, для умышленного нанесения убытков кому- либо или в любых корыстных целях.</p>
			<p>4.2.3. Присоединяясь к настоящему Соглашению, Агент понимает, принимает и соглашается с тем, что он:
				несет полную личную ответственность за содержание и соответствие нормам украинского и международного законодательства всех Материалов, включая все тексты, программы, музыку, звуки, фотографии, графику, видео и т.д.
				несет полную личную ответственность за соответствие способов использования им Материалов других Пользователей и другой информации, представленной на Портале, нормам украинского или международного права (в том числе, но не ограничиваясь, нормам права об интеллектуальной собственности и о защите информации);
				несет полную ответственность за сохранность своей учетной записи (логина и пароля), а также за все действия, совершенные под своей учетной записью;
				использует Портал на свой собственный риск.</p>
			<p>4.2.4. В случае нарушения Агентом  какого-либо из условий настоящего Соглашения Администрация оставляет за собой право прекратить доступ Агента к Порталу (в том числе путем блокирования доступа к серверу IP-адреса, с которого была осуществлена регистрация данного Агента/было размещено наибольшее количество Материалов данного Агента) и передать Материалы, подтверждающие незаконные действия Агента, для принятия мер в правоохранительные органы.</p>
			<p>4.2.5. Агент соглашается с тем, что возместит Администрации любые убытки, понесенные Администрацией в связи с использованием Агентом Портала, нарушением Агентом настоящего Соглашения и прав (в том числе интеллектуальных, информационных и т.д.) третьих лиц.</p>
			<p>4.2.6. Агент признает и соглашается, что IP-адрес персональной ЭВМ Агента фиксируется техническими средствами Администрации, и в случае совершения незаконных действий, в том числе действий, нарушающих интеллектуальные права третьих лиц, ответственным за указанные незаконные действия признается владелец персональной ЭВМ, определяемой техническими средствами Администратора по принадлежности IP-адреса.</p>

			<p class="section">5. ИНТЕЛЛЕКТУАЛЬНЫЕ ПРАВА</p>
			<p>5.1. Портал, его составляющие и отдельные компоненты (в том числе, но не ограничиваясь: программы для ЭВМ, базы данных, коды, лежащие в их основе, ноу-хау, алгоритмы, элементы дизайна, шрифты, логотипы, а также текстовые, графические и иные материалы) являются объектами интеллектуальной собственности, охраняемыми в соответствии с национальным и международным законодательством, любое использование которых допускается только на основании разрешения правообладателя.</p>
			<p>5.2. Незаконное использование указанных в п. 5.1. настоящего Соглашения объектов интеллектуальной собственности влечет гражданскую, административную и уголовную ответственность.</p>
			<p>5.3. Агент не вправе осуществлять в отношении Портала, их составляющих и компонентов воспроизведение (тиражирование и иное копирование), распространение, модификацию, переформатирование и иную переработку. Любые компоненты Портала запрещается использовать в составе других сайтов, программных продуктов, поисковых систем, других произведений и объектов смежных прав, копировать или иным способом использовать с целью извлечения материальной выгоды.</p>

			<p class="section">6. ФОРС-МАЖОР И ЧРЕЗВЫЧАЙНЫЕ ОБСТОЯТЕЛЬСТВА</p>
			<p>6.1. Стороны не несут ответственности за нарушение своих обязательств, которые возникли после вступления в силу настоящего Соглашения, если такое нарушение вызвано форс-мажорными обстоятельствами.</p>
			<p>6.2. Форс-мажорные обстоятельства означают чрезвычайные обстоятельства вне разумного контроля Сторон, включая, но не ограничиваясь, следующими обстоятельствами:
				война или другие военные действия (независимо от того, является ли война объявленной или необъявленной), оккупация, действия иностранных противников, мобилизация, реквизиция или эмбарго;
				ионизирующая радиация или радиоактивное заражение, вызванное определенным видом ядерного топлива или ядерных отходов, полученных в результате сжигания ядерного топлива, токсичных радиоактивных взрывчатых веществ и других вредных свойств взрывчатых или взрывных ядерных устройств или ядерных компонентов;
				перевороты, революции, бунты, военные диктатуры или захват власти и гражданскую войну;
				пожары, землетрясения, наводнения;
				акты и действия государственных органов, делающие невозможным исполнение обязательств по настоящему Договору в соответствии с законным порядком.</p>

			<p class="section">7. РАЗРЕШЕНИЕ СПОРОВ И УДОВЛЕТВОРЕНИЕ ПРЕТЕНЗИЙ</p>
			<p>7.1. Все споры и претензии регулируются на основании положений настоящего Соглашения, а в случае их не урегулирования — в порядке, установленном действующим законодательством Украины.</p>
			<p>7.2. Любые вопросы, комментарии и иная корреспонденция Агента должны направляться Администрации по адресам и телефонам, указанным на странице Контакты на сайте www.xt.ua. Администрация не несет ответственности и не гарантирует ответ на запросы, вопросы, предложения и иную информацию, направленные ему любым другим способом.</p>
			<p>7.3. Возникшие в связи с настоящим Соглашением претензии, связанные с нарушением интеллектуальных прав третьих лиц, направляются Агентом Администрации Портала по следующему адресу электронной почты: contact@ xt.ua. Администрация обязуется в течение 10 (десяти) рабочих дней ответить на данную претензию, направив письмо с изложением своей позиции по указанному в претензии адресу электронной почты. При этом претензии Агентов, которых не представляется возможным идентифицировать на основе предоставленных им при регистрации данных (в том числе анонимные претензии), Администрацией не рассматриваются. В случае если Агент не согласен с мотивами, приведенными Администрацией в ответе на претензию, процедура ее урегулирования повторяется при помощи направления мотивированного ответа Агента с использованием почтовой связи, а именно заказным письмом с уведомлением. В случае невозможности разрешения претензии путем переговоров, спор разрешается в порядке, предусмотренным настоящим Соглашением.</p>
			<p>7.4. Агент и Администрация соглашаются, что все возможные споры, возникшие в связи с настоящим Соглашением, разрешаются сторонами по нормам украинского права и рассматриваются по месту нахождения Администрации.</p>

			<p class="section">8. СРОК ДЕЙСТВИЯ СОГЛАШЕНИЯ</p>
			<p>8.1. Настоящее Соглашение заключается между сторонами на неопределенный срок при условии непрерывного прироста Пользователей Агента. Ежемесячно Агент обеспечивает регистрацию минимум двух новых Пользователей, данный процесс является подтверждением статуса агента и основным критерием для автоматического продления статуса агента еще на один календарный месяц.</p>
			<p>8.2. Агент получает дополнительный доход от покупок с его промокодом только при действительности его статуса.</p>
			<p>8.2. В случае, если Агент  не пользуется Порталом в течение 90 календарных дней, а также в случае нарушения Агентом условий настоящего Соглашения Администрация вправе прекратить действие логина и пароля Агента. Логин и пароль Агента будут сохранены с возможностью повторной активации в течение 90 дней после прекращения возможности использования Портала Агентом. Для активации Агент должен обратиться к Администрации для повторного получения ссылки активации на указанный при первой регистрации электронный адрес. При этом право предоставления либо не предоставления Агенту повторной активации принадлежит Администрации.</p>

			<p class="section">9. ДОПОЛНИТЕЛЬНЫЕ УСЛОВИЯ</p>
			<p>9.1. Администрация оставляет за собой право в одностороннем порядке и без предварительного уведомления Агентов изменять условия Соглашения, разместив при этом окончательную версию Соглашения на странице по адресу http://xt.ua /page/Dogovor/ за 5 (пять) дней до вступления изменений в силу. Положения новой редакции Агентского Соглашения становятся обязательными для всех ранее зарегистрировавшихся Агентов Сайта.</p>
			<p>9.2. Настоящее Соглашение ни при каких обстоятельствах не может быть трактовано как договор об установлении трудовых отношений, отношений товарищества, отношений по совместной деятельности, отношений личного найма, либо каких-то иных отношений между Агентом  и Администратором, прямо не указанных в настоящем Cоглашении.</p>
			<p class="section">Посещение сайтов третьих лиц
			Сайт Портала может содержать ссылки на другие сайты и сервисы третьих лиц, которые не контролируются Администрацией. Администрация не несет ответственности за содержимое, правила пользования и политику безопасности сайтов третьих лиц.
			Администрация не несет ответственности за действия и последствия действий Посетителей и Пользователей, которые переходят по ссылкам с Портала на сайты третьих лиц.</p>
		</div>
		<div class="confirm_block">
			<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="confirm">
				<input type="checkbox" id="confirm" class="mdl-checkbox__input confirm_checkbox_js">
				<span class="mdl-checkbox__label confirm_text">Я принимаю условия соглашения</span>
			</label>
			<form action="">
				<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored confirm_btn_js" name="confirm_agent" disabled="disabled">Продолжить</button>
			</form>
		</div>
	</div>
<?}?>