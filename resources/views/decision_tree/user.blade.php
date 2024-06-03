<!DOCTYPE html>
<html>
<head>
    <title>Coren-RJ Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    
        .menu-title {
            width: 100%;
            text-align: center;
            margin-bottom: 0.5em;
            font-weight: bolder;
            font-size: larger;
        }

        .menu-subtitle {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
            font-size: larger;
            display: none;
            font-weight: bold;
        }
    
        .menu-container {
            display: none;
            text-align: center;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        
        .menu-container.active {
            display: flex;
        }
    
        .menu-button {
            margin: 10px 0;
            padding: 15px;
            font-size: 3.25em;
            max-width: 600px;
            display: block;
            width: 95%;
            box-sizing: border-box;
        }
    
    </style>
</head>
<body>
    <!-- Logo -->
    <div class="container  d-flex flex-column align-items-center">
        <div id="title" class="menu-title">
            <div class="shrink-0 flex items-center justify-center mt-4">
                <img src="https://www.coren-rj.org.br/site/wp-content/uploads/2018/11/logo-horizontal.png" alt="logo" class="block h-12" />
            </div>
        </div>
        <div id="subtitle" class="menu-subtitle"></div>
        <div id="menu-root" class="menu-container active" data-parent-id="">
            @foreach ($rootMenus as $menu)
                <button class="btn btn-primary btn-sm menu-button" data-id="{{ $menu->id }}" data-action="{{ $menu->action_type }}" data-value="{{ $menu->action_value }}" data-instruction="{{ $menu->instruction ? $menu->instruction->content : '' }}">{{ $menu->label }} </button>
            @endforeach
        </div>
        @foreach($menus as $menu)
            <div id="menu-{{ $menu->id }}" class="menu-container" data-parent-id="root" data-subtitle="{{ $menu->label}}">
                @foreach($menu->children as $child)
                    <button class="btn btn-primary menu-button" data-id="{{ $child->id }}" data-action="{{ $child->action_type }}" data-value="{{ $child->action_value }}" data-instruction="{{ $child->instruction ? $child->instruction->content: '' }}">{{ $child->label }}</button>
                @endforeach
                <button class="btn btn-secondary btn-sm menu-button back-button">Voltar</button>
            </div>
        @endforeach
    </div>

    <!-- Modal for Instructions -->
    <div class="modal fade" id="instructionModal" tabindex="-1" role="dialog" aria-labelledby="instructionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructionModalLabel">Instruções</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="instructionText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmActionButton">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var actionType, actionValue, id;

            $('.menu-button').click(function() {
                id = $(this).data('id');
                actionType = $(this).data('action');
                actionValue = $(this).data('value');
                var instruction = $(this).data('instruction');
                
                if (instruction) {
                    $('#instructionText').html(instruction);
                    $('#instructionModal').modal('show');
                } else {
                    executeAction(actionType, actionValue, id);
                }
            });

            $('#confirmActionButton').click(function() {
                executeAction(actionType, actionValue, id);
                $('#instructionModal').modal('hide');
            });

            function executeAction(actionType, actionValue, id) {
                if (actionType === 'external_link') {
                    window.open(actionValue, '_blank');
                } else if (actionType === 'sub_option') {
                    var currentMenu = $('.menu-container.active');
                    currentMenu.removeClass('active');
                    $('#menu-' + id).addClass('active');
                    $('#subtitle').text($('#menu-' + id).data('subtitle')).show();
                } else if (actionType === 'schedule') {
                    window.location.href = '/scheduling?node_id=' + id;
                }
            }

            $('.back-button').click(function() {
                var currentMenu = $(this).closest('.menu-container');
                var parentId = currentMenu.data('parent-id');
                
                currentMenu.removeClass('active');
                
                if (parentId) {
                    var parentMenu = $('#menu-' + parentId);
                    parentMenu.addClass('active');
                    var parentSubtitle = parentMenu.data('subtitle');
                    $('#subtitle').text(parentSubtitle);
                } else {
                    $('#menu-root').addClass('active');
                    $('#subtitle').hide();
                }
            });
        });
    </script>
</body>
</html>
