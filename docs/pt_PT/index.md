Descrição 
===========

Este plugin permite que você controle seu Karotz (executando sob
[OpenKarotz](http://www.openkarotz.org/)) Vai de seu led ventral, para
seus ouvidos passando pelos sons, síntese de fala e
d'autres.

Configuração 
=============

Configuração do plugin Jeedom : 
--------------------------------

**Instalação / Criação**

Para usar o plug-in, você precisa baixar, instalar e
ativá-lo como qualquer plugin Jeedom.

Vá para o menu Plugins / Comunicação, você encontrará o
Karotz plugin.

Você chegará à página que listará seu equipamento (você pode
Karotz) e que permitirá que você os crie.

Clique no botão àdicionar :

Você chegará à página de configuração do seu karotz.

-   **Commandes**

Você não tem nada para fazer nesta seção. Os pedidos serão criados
automatiquement.

-   Legal: para atualizar o widget, se necessário

-   Piscando Desligado : permite parar o piscar do led

-   Piscando em : ativa o piscar do led

-   Pare o som : interrompe uma música ou um som em andamento

-   Hora de dormir : deixa o coelho dormir

-   Em pé : acorde o coelho

-   Permanente em silêncio : permite acordar o coelho no modo silencioso

-   Relógio : permite iniciar o modo de relógio de coelho

-   Humor : permite que o coelho conte o humor selecionado

-   Humor No.: permite ao coelho dizer o humor indicado pelo seu
    n °

-   Humor aleatório : deixa o coelho dizer um humor
    aleatório

-   Orelha aleatória : permite que você mova seus ouvidos
    aleatório

-   Ear RàZ : permite retornar os ouvidos à posição inicial

-   Orelhas Posições : ajusta a posição precisa dos dois
    ouvidos

-   Som de Karotz (nome) : permite iniciar um som de Karotz (bip
    por exemplo) indicando o nome dele

-   Som Karotz : permite lançar um som de Karotz (sinal sonoro, por exemplo)
    selecionando o nome dele em uma lista

-   URL dele : permite que um URL seja lido pelo Karotz (estação de rádio
    por exemplo)

-   Squeezebox ativado : permite ativar o modo squeezebox Karotz

-   Squeezebox off : permite desativar o modo squeezebox Karotz

-   àdormecido : avisa se o Karotz está dormindo (caso contrário,
    está acordado)

-   Status da cor : permite ter a cor atualmente no
    Karotz Barriga

-   TTS : permite que o coelho fale escolhendo a voz e o
    mensagem (por padrão, a mensagem é armazenada em cache)

-   TTS sem cache : permite que o coelho fale escolhendo a
    voz e mensagem (a mensagem não é armazenada em cache)

-   Velocidade de pulso : ajusta a velocidade do piscar

-   % de espaço ocupado : permite saber a% de disco usado no
    o coelho

-   Espaço livre : valor em MB de espaço livre no coelho

-   Restart : permite reiniciar o coelho

-   Definir hora : retorna automaticamente o coelho para
    a hora (útil para alterar a hora)

-   Nível de volume : indica em% o nível do volume

-   Volume : permite escolher em% o nível do volume (máximo recomendado
    50%, risco de distorção acima)

-   Volume + : aumenta o nível do volume em 5%

-   Volume- : diminui o nível do volume em 5%

-   Foto : permite tirar uma foto do coelho

-   Fotos excluir : permite excluir todas as fotos tiradas pelo
    coelho (libera espaço em disco)

-   Fotos atualizar lista : permite atualizar a lista de fotos
    preservado

-   Listagem de fotos : lista de fotos mantidas

-   Download de fotos : permite baixar (por ftp) as fotos
    mantido no disco (eles não são excluídos)

Todos esses comandos estão disponíveis através dos cenários.

Comando TTS 
------------

O comando TTS pode ter várias opções separadas por & :

-   voz : o número da voz

-   nocache : não use o cache

Exemplo :

    voz = 3 e nocache = 1

…

Informações / ações 
========================

Informações / ações no painel : 
---------------------------------------

![widget](../images/widget.jpg)

-   à : àcesse a página de seleção de som

![karotz screenshot5](../images/karotz_screenshot5.jpg)

-   B : Botão àtualizar para solicitar status e
    Cor

-   C : Zona de controle do ouvido (aleatória, redefinida
    zero, personalizado)

![karotz screenshot7](../images/karotz_screenshot7.jpg)

-   D : Área de ações (relógio / humor)

-   E : Área Squeezebox (ativar / desativar)

-   F : Zona do LED (ativar intermitente / desativar)

![karotz screenshot6](../images/karotz_screenshot6.jpg)

-   G : Controle deslizante de velocidade do flash

-   H : ào clicar na barriga, isso permite que você altere a cor do
    o led

-   Eu : ào clicar no coelho, ele permite que ele se deite ou
    adormecer

Faq 
===

Com que frequência as informações são atualizadas:   

 O sistema recupera informações a cada 30 minutos ou após
    um pedido para alterar a cor ou condição do coelho. Você pode
    clique no comando àtualizar para atualizar manualmente.

Registro de alterações detalhado :
<https://github.com/jeedom/plugin-karotz/commits/stable>
