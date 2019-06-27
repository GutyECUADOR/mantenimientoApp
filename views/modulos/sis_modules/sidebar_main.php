<aside id="sidebar_main">
        
        <div class="sidebar_main_header">
            <div class="sidebar_logo">
                <a class="sSidebar_hide sidebar_logo_large">
                  
                    <img class="logo_regular" src="assets/img/logo_dark.png" alt="" height="15" width="71"/>
                    
                </a>
            </div>
            <div class="sidebar_actions">
                <strong><?php echo $_SESSION["empresaAUTH"];?></strong>
            </div>
            
        </div>
        
        <?php
            if (isset($_SESSION["usuarioRUC"])){
        ?>

            <div class="menu_section">
                <ul>
                    <li title="Dashboard">
                        <a href="?action=inicio">
                            <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>
                            <span class="menu_title">Dashboard</span>
                        </a>
                    
                    </li>
                    
                    
                    <li title="Mantenimiento de Equipos">
                        <a href="#">
                            <span class="menu_icon"><i class="material-icons">build</i></span>
                            <span class="menu_title">Mantenimientos</span>
                        </a>
                        <ul>
                           
                            <li><a href="?&action=mantenimientosEQ">Agendar mantenimiento</a></li>
                            <li><a href="?&action=mantenimientosAG">Mantenimientos Agendados</a></li>
                            <li><a href="?&action=mantenimientosHistorico">Historico Internos</a></li>
                            <li><a href="?&action=mantenimientosEXT">Equipos externos</a></li>
                            <li><a href="?&action=mantenimientosHistoricoEXT">Historico Externos</a></li>
                        
                        </ul>
                    
                    </li>
                    
                    <li title="Supervisores">
                        <a href="#">
                            <span class="menu_icon"><i class="material-icons">remove_red_eye</i></span>
                            <span class="menu_title">Supervisores</span>
                        </a>
                        <ul>
                            <li><a href="?&action=checkListSupervisoresBasicas">Actividades Basicas a cumplir</a></li>
                            <li><a href="?&action=tableListActBasicasSup">Resumen mensual</a></li>
                        </ul>
                    </li>

                    <li title="Configuracion">
                        <a href="?action=configuracionSis">
                            <span class="menu_icon"><i class="material-icons">settings</i></span>
                            <span class="menu_title">Configuracion</span>
                        </a>
                    
                    </li>
                </ul>
            </div>

        <?php 
            }else {
        ?>

            <div class="menu_section">
                <ul>
                    
                </ul>
            </div>

        <?php 
            }
        ?> 
</aside>