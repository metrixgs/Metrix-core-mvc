var V=Object.defineProperty;var q=(n,e,t)=>e in n?V(n,e,{enumerable:!0,configurable:!0,writable:!0,value:t}):n[e]=t;var B=(n,e,t)=>q(n,typeof e!="symbol"?e+"":e,t);(function(){const e=document.createElement("link").relList;if(e&&e.supports&&e.supports("modulepreload"))return;for(const i of document.querySelectorAll('link[rel="modulepreload"]'))s(i);new MutationObserver(i=>{for(const a of i)if(a.type==="childList")for(const o of a.addedNodes)o.tagName==="LINK"&&o.rel==="modulepreload"&&s(o)}).observe(document,{childList:!0,subtree:!0});function t(i){const a={};return i.integrity&&(a.integrity=i.integrity),i.referrerPolicy&&(a.referrerPolicy=i.referrerPolicy),i.crossOrigin==="use-credentials"?a.credentials="include":i.crossOrigin==="anonymous"?a.credentials="omit":a.credentials="same-origin",a}function s(i){if(i.ep)return;i.ep=!0;const a=t(i);fetch(i.href,a)}})();class j{constructor(){this.map=null,this.layerControl=null,this.isInitialized=!1,this.baseLayerInstances=new Map,this.eventHandlers=new Map}initialize(e,t){if(this.isInitialized)return console.warn("Map already initialized"),this.map;try{return this.map=L.map(e,t).setView([14.0906,-87.2054],6),this.isInitialized=!0,this.setupErrorHandling(),this.initializeBaseLayers(),this.initializeControls(),this.map}catch(s){throw console.error("Error initializing map:",s),this.isInitialized=!1,s}}initializeBaseLayers(){const e={"Jawg Maps":L.tileLayer("https://tile.jawg.io/6ce62bcb-c195-4d31-a3ce-421b1d40f3bd/{z}/{x}/{y}{r}.png?access-token=xpGCLKVCsTyKo9B2QbcI9mKUWCpJdS4PEpT1rsVCeZoENPdujT3KjjiEe9YLIwCO",{}),OpenStreetMap:L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{attribution:"© OpenStreetMap contributors"}),"Dark Matter":L.tileLayer("https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",{maxZoom:19,attribution:"© OpenStreetMap contributors"}),Satellite:L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",{attribution:"Esri"})};Object.entries(e).forEach(([t,s])=>{this.baseLayerInstances.set(t,s)}),this.baseLayerInstances.get("Jawg Maps").addTo(this.map),this.layerControl=L.control.layers(e,{},{position:"topright",collapsed:!0}).addTo(this.map)}initializeControls(){L.control.zoom({position:"topright"}).addTo(this.map),L.control.locate({position:"topright",strings:{title:"Show my location"},flyTo:!0,cacheLocation:!0,showCompass:!0,showPopup:!1}).addTo(this.map)}switchToSatellite(){const e=this.baseLayerInstances.get("Satellite"),t=this.baseLayerInstances.get("Jawg Maps");e&&!this.map.hasLayer(e)&&(t&&this.map.removeLayer(t),this.map.addLayer(e))}switchToDefault(){const e=this.baseLayerInstances.get("Satellite"),t=this.baseLayerInstances.get("Jawg Maps");t&&!this.map.hasLayer(t)&&(e&&this.map.removeLayer(e),this.map.addLayer(t))}setupErrorHandling(){this.map.on("error",e=>{console.error("Map error:",e)}),window.addEventListener("offline",()=>{console.warn("Connection lost - some map features may be unavailable")}),window.addEventListener("online",()=>{console.log("Connection restored - refreshing map features"),this.refreshVisibleLayers()})}refreshVisibleLayers(){this.map&&this.map.eachLayer(e=>{e instanceof L.TileLayer&&e.redraw()})}getMap(){return this.isInitialized||console.warn("Map not initialized"),this.map}setLayerControl(e){this.layerControl=e}getLayerControl(){return this.layerControl}addEventHandler(e,t){this.map&&(this.eventHandlers.has(e)||this.eventHandlers.set(e,new Set),this.eventHandlers.get(e).add(t),this.map.on(e,t))}removeEventHandler(e,t){if(!this.map)return;const s=this.eventHandlers.get(e);s!=null&&s.has(t)&&(this.map.off(e,t),s.delete(t))}destroy(){this.map&&(this.map.remove(),this.map=null,this.layerControl=null,this.isInitialized=!1,this.baseLayerInstances.clear(),this.eventHandlers.clear())}}const c=new j;class Z{constructor(e){this.map=e,this.heatmapLayer=null,this.points=[],this.isVisible=!1,this.intensity=1,this.radius=30,this.blur=20,this.maxZoom=15}setData(e){this.points=e.map(t=>{const s=parseFloat(t.latitud),i=parseFloat(t.longitud);return[s,i,this.intensity]}).filter(t=>!isNaN(t[0])&&!isNaN(t[1]))}show(){try{this.heatmapLayer||(this.heatmapLayer=L.heatLayer(this.points,{radius:this.radius,blur:this.blur,maxZoom:this.maxZoom,gradient:{.2:"#3388ff",.4:"#00A650",.6:"#ffb300",.8:"#ff3d00",1:"#d50000"},minOpacity:.3})),this.map.addLayer(this.heatmapLayer),this.isVisible=!0}catch(e){console.error("Error showing heatmap:",e)}}hide(){try{this.heatmapLayer&&this.map.removeLayer(this.heatmapLayer),this.isVisible=!1}catch(e){console.error("Error hiding heatmap:",e)}}toggle(){return this.isVisible?this.hide():this.show(),this.isVisible}updateData(e){this.setData(e),this.isVisible&&this.heatmapLayer&&this.heatmapLayer.setLatLngs(this.points)}setOptions(e={}){const{intensity:t,radius:s,blur:i,maxZoom:a}=e;t!==void 0&&(this.intensity=t),s!==void 0&&(this.radius=s),i!==void 0&&(this.blur=i),a!==void 0&&(this.maxZoom=a),this.heatmapLayer&&this.isVisible&&(this.hide(),this.show())}}const f=new Z;class G{constructor(){this.map=null,this.hexLayer=null,this.points=[],this.isVisible=!1,this.resolution=7,this.hexagons=new Map,this.colorScale=["#ff0000","#ff3300","#ff6600","#ff9900","#ffcc00","#ffff00"],this.zoomResolutions={0:3,4:4,6:5,8:6,10:7,12:8,14:9}}setMap(e){this.map=e,e&&e.on("zoomend",()=>this.handleZoomChange())}handleZoomChange(){if(!this.isVisible||!this.map)return;const e=this.map.getZoom(),t=this.getResolutionForZoom(e);t!==this.resolution&&(this.resolution=t,this.processHexagons(),this.updateLayer())}getResolutionForZoom(e){let t=7;for(const[s,i]of Object.entries(this.zoomResolutions))if(e>=parseInt(s))t=i;else break;return t}setData(e){this.points=e.filter(t=>{const s=parseFloat(t.latitud),i=parseFloat(t.longitud);return!isNaN(s)&&!isNaN(i)}),this.isVisible&&(this.processHexagons(),this.updateLayer())}processHexagons(){this.hexagons.clear(),this.points.forEach(e=>{const t=parseFloat(e.latitud),s=parseFloat(e.longitud),i=window.h3.latLngToCell(t,s,this.resolution);this.hexagons.has(i)||this.hexagons.set(i,{count:0,points:[],center:window.h3.cellToLatLng(i)});const a=this.hexagons.get(i);a.count++,a.points.push(e)})}getHexagonStyle(e){const t=Math.max(...Array.from(this.hexagons.values()).map(o=>o.count)),s=e/t,i=Math.floor(s*(this.colorScale.length-1));return{fillColor:this.colorScale[i],fillOpacity:.7,color:"#ffffff",weight:1,opacity:.8}}updateLayer(){if(!this.map||!this.isVisible)return;this.hexLayer&&this.map.removeLayer(this.hexLayer);const e=[];this.hexagons.forEach((t,s)=>{const i=window.h3.cellToBoundary(s,!0),a={type:"Feature",properties:{hexId:s,count:t.count,points:t.points,center:t.center},geometry:{type:"Polygon",coordinates:[i]}};e.push(a)}),this.hexLayer=L.geoJSON({type:"FeatureCollection",features:e},{style:t=>this.getHexagonStyle(t.properties.count),onEachFeature:(t,s)=>{const i=t.properties.count;s.bindPopup(this.createPopupContent(t.properties)),s.on("mouseover",()=>{s.setStyle({fillOpacity:.9,weight:2,opacity:1})}),s.on("mouseout",()=>{s.setStyle(this.getHexagonStyle(i))})}}).addTo(this.map)}createPopupContent(e){return`
            <div class="p-3">
                <h3 class="text-lg font-semibold mb-2">Densidad de Incidencias</h3>
                <p class="text-sm font-medium">Total: ${e.count} incidencia${e.count!==1?"s":""}</p>
                <p class="text-xs text-gray-500 mt-1">Resolución H3: ${this.resolution}</p>
            </div>
        `}show(){!this.map||this.isVisible||(this.isVisible=!0,this.resolution=this.getResolutionForZoom(this.map.getZoom()),this.processHexagons(),this.updateLayer())}hide(){this.hexLayer&&this.map&&(this.map.removeLayer(this.hexLayer),this.hexLayer=null),this.isVisible=!1}toggle(){return this.isVisible?this.hide():this.show(),this.isVisible}}const y=new G,k={alta:{class:"bg-red-100 text-red-800",icon:"bi-exclamation-triangle-fill"},media:{class:"bg-yellow-100 text-yellow-800",icon:"bi-exclamation-circle-fill"},baja:{class:"bg-green-100 text-green-800",icon:"bi-info-circle-fill"}};class R{static createPopupContent(e){const t=new Date(e.fecha_creacion).toLocaleString(),s=e.fecha_vencimiento?new Date(e.fecha_vencimiento).toLocaleString():"No establecida",i=o=>{const r={abierto:"bg-yellow-100 text-yellow-800","en proceso":"bg-blue-100 text-blue-800",cerrado:"bg-green-100 text-green-800"};return r[o.toLowerCase()]||r.abierto},a=o=>{var r;return((r=k[o.toLowerCase()])==null?void 0:r.class)||k.media.class};return`
            <div class="p-4 min-w-[300px]">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-mono text-gray-600 text-sm">#${e.identificador}</span>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${a(e.prioridad)}">
                            ${e.prioridad}
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${i(e.estado)}">
                            ${e.estado}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-primary mb-4">${e.titulo}</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex">
                        <span class="w-24 text-sm text-gray-500">Creado:</span>
                        <span class="text-sm">${t}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm text-gray-500">Vence:</span>
                        <span class="text-sm">${s}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm text-gray-500">Cliente:</span>
                        <span class="text-sm">${e.nombre_cliente}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm text-gray-500">Área:</span>
                        <span class="text-sm">${e.nombre_area}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm text-gray-500">Ubicación:</span>
                        <span class="text-sm">${e.direccion_completa}</span>
                    </div>
                </div>

                <button onclick="window.showIncidentDetails(${JSON.stringify(e).replace(/"/g,"&quot;")})"
                        class="w-full py-2 px-4 bg-primary hover:bg-secondary text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                    Ver Detalles
                </button>
            </div>
        `}}class U{static handleAPIError(e,t=""){return console.error(`Error en API ${t}:`,e),{success:!1,error:e.message,data:[]}}static validatePoint(e){try{if(!e||typeof e!="object")return!1;const t=parseFloat(e.latitud),s=parseFloat(e.longitud);return isNaN(t)||isNaN(s)||t<-90||t>90||s<-180||s>180||!["estado","identificador","titulo"].every(o=>o in e)?!1:(["estado","titulo","descripcion","nombre_cliente","nombre_area"].forEach(o=>{e[o]&&(e[o]=this.sanitizeText(e[o]))}),!0)}catch(t){return console.error("Error validando punto:",t),!1}}static sanitizeText(e){return e?(e=String(e),e=e.replace(/<[^>]*>/g,""),e=e.replace(/[\x00-\x1F\x7F-\x9F]/g,""),e=e.trim(),e):""}static getDefaultPoint(){return{latitud:0,longitud:0,estado:"Sin estado",identificador:"N/A",titulo:"Sin título",descripcion:"Sin descripción",nombre_cliente:"Sin cliente",nombre_area:"Sin área",fecha_creacion:new Date().toISOString(),prioridad:"Baja",color_sla:"#cccccc"}}}const C={adminBoundaries:{name:"Límites Administrativos",layers:[{id:"limite_mxestados",url:"https://espacialhn.com/slim4/api/api/sinit/limite_mxestados/",name:"Estados de México",style:{color:"#3388ff",weight:2,opacity:1,fillOpacity:.1}}]},incidents:{name:"Incidencias",layers:[{id:"incidencias",url:"https://api.metrixmobile.xyz/api/incidencias",name:"Incidencias",style:{radius:8,fillColor:"#ef4444",color:"#ffffff",weight:1,opacity:1,fillOpacity:.8}}]}},x={limite_mxestados:{minZoom:5,maxZoom:9,fadeZoom:{in:5,out:9}}};class J{constructor(){this.layer=null,this.currentStateId=null,this.isLoading=!1}loadMunicipalities(e,t){if(this.isLoading){console.warn("Ya hay una carga de municipios en proceso");return}const s=c.getMap();if(!s){console.error("Mapa no inicializado");return}try{if(this.isLoading=!0,this.layer){s.removeLayer(this.layer);const o=c.getLayerControl();o&&this.currentStateId&&o.removeLayer(this.layer),this.layer=null}const i=`municipalityCallback_${e}_${Date.now()}`;window[i]=o=>{this.handleMunicipalityData(o,t),delete window[i],this.isLoading=!1};const a=document.createElement("script");a.src=`https://espacialhn.com/slim4/api/api/sinit/limite_mxmunicipios/?entidad=${e}&callback=${i}`,a.onerror=()=>{console.error("Error al cargar datos de municipios"),delete window[i],this.isLoading=!1},document.body.appendChild(a)}catch(i){console.error("Error en carga de municipios:",i),this.isLoading=!1}}handleMunicipalityData(e,t){var i,a;const s=c.getMap();if(!s||!e||!e.features){console.error("Datos de municipios inválidos o mapa no disponible");return}try{this.layer=L.geoJSON(e,{style:{color:"#00A650",weight:2,opacity:1,fillOpacity:.1},onEachFeature:(r,l)=>{const d=r.properties;l.bindPopup(`
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-emerald-600 mb-2">
                                ${d.nombre||"Municipio"}
                            </h3>
                            <div class="text-sm text-gray-600">
                                <p>Estado: ${t}</p>
                                <p>Código: ${d.municipio||"N/A"}</p>
                            </div>
                        </div>
                    `),l.on("mouseover",()=>{l.setStyle({fillOpacity:.3,weight:3})}),l.on("mouseout",()=>{l.setStyle({fillOpacity:.1,weight:2})})}}).addTo(s),s.fitBounds(this.layer.getBounds());const o=c.getLayerControl();o&&o.addOverlay(this.layer,`Municipios de ${t}`),this.currentStateId=(a=(i=e.features[0])==null?void 0:i.properties)==null?void 0:a.entidad,console.log(`Municipios de ${t} cargados exitosamente`)}catch(o){console.error("Error al procesar datos de municipios:",o),this.clear()}}clear(){const e=c.getMap();if(e&&this.layer){e.removeLayer(this.layer);const t=c.getLayerControl();t&&t.removeLayer(this.layer),this.layer=null,this.currentStateId=null}}isActive(){return this.layer!==null}}const W=new J;class K{constructor(){this.layers=new Map,this.map=null,this.activeLayerIds=new Set,this.incidentsLayer=null}init(e){this.map=e,this.setupZoomHandler()}setupZoomHandler(){this.map&&this.map.on("zoomend",()=>{this.handleZoomChange(this.map.getZoom())})}handleZoomChange(e){this.layers.forEach((t,s)=>{const i=x[s];if(!i)return;const{layer:a}=t,{minZoom:o,maxZoom:r}=i,l=e>=o&&e<=r,d=this.map.hasLayer(a);if(l!==d&&(l?this.activeLayerIds.has(s)&&this.map.addLayer(a):this.map.removeLayer(a)),l&&d){const m=this.getZoomBasedStyle(e,s);a.setStyle(m)}})}getZoomBasedStyle(e,t){var i;const s=((i=C.adminBoundaries.layers.find(a=>a.id===t))==null?void 0:i.style)||{};return e<=6?{...s,weight:1,fillOpacity:.05}:e>=8?{...s,weight:2.5,fillOpacity:.15}:s}toggleLayer(e){if(e==="incidencias")return this.toggleIncidentsLayer();const t=this.layers.get(e);if(!t)return!1;const{layer:s}=t,i=this.map.getZoom(),a=x[e],o=a?i>=a.minZoom&&i<=a.maxZoom:!0;return this.activeLayerIds.has(e)?(this.activeLayerIds.delete(e),o&&this.map.removeLayer(s),!1):(this.activeLayerIds.add(e),o&&this.map.addLayer(s),!0)}toggleIncidentsLayer(){return this.incidentsLayer?this.map.hasLayer(this.incidentsLayer)?(this.map.removeLayer(this.incidentsLayer),!1):(this.map.addLayer(this.incidentsLayer),!0):!1}registerIncidentsLayer(e){this.incidentsLayer=e}loadLayer(e){if(!this.map){console.error("Map not initialized");return}if(this.layers.has(e.id)){console.log(`Layer ${e.id} already loaded`);return}const t=`wfsCallback_${e.id}`;window[t]=i=>{this.handleWFSData(i,e),delete window[t]};const s=document.createElement("script");s.src=`${e.url}?callback=${t}`,document.body.appendChild(s)}handleWFSData(e,t){if(!e||!e.features){console.error(`Invalid WFS data for layer ${t.id}`);return}const s=L.geoJSON(e,{style:t.style,interactive:!0,onEachFeature:(o,r)=>{o.properties&&r.bindPopup(this.createPopupContent(o.properties)),r.on("mouseover",l=>{l.target.setStyle({color:"#00A650",weight:3,fillOpacity:.2})}),r.on("mouseout",l=>{l.target.setStyle(t.style)}),r.on("click",l=>{this.layers.forEach(d=>{d.layer.eachLayer(m=>{m.setStyle(d.config.style)})}),l.target.setStyle({color:"#00A650",weight:3,fillOpacity:.3})})}});this.layers.set(t.id,{layer:s,config:t});const i=this.map.getZoom(),a=x[t.id];if(a&&i>=a.minZoom&&i<=a.maxZoom){s.addTo(this.map);const o=this.getZoomBasedStyle(i,t.id);s.setStyle(o)}}createPopupContent(e){return`
            <div class="p-4">
                <h3 class="text-lg font-semibold text-emerald-600 mb-2">${e.nombre||"Sin nombre"}</h3>
                <div class="text-sm text-gray-600 mb-4">
                    <p>Entidad: ${e.entidad||"N/A"}</p>
                </div>
                <button onclick="window.loadStateMunicipalities('${e.entidad}', '${e.nombre}')" 
                        class="w-full py-2 px-4 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors text-sm font-medium">
                    Ver Municipios
                </button>
            </div>
        `}loadAllLayers(){if(!this.map){console.error("Map not initialized");return}Object.values(C).forEach(e=>{e.layers.forEach(t=>{this.loadLayer(t)})})}}window.loadStateMunicipalities=(n,e)=>{W.loadMunicipalities(n,e)};const v=new K;class Y{constructor(){this.pointsLayer=null,this.clusterLayer=null,this.points=[],this.totalPoints=0,this.visiblePointsCount=0,this.setupToggles()}setupToggles(){const e=document.getElementById("heatmapToggle");e&&e.addEventListener("click",()=>{var a;const s=f.toggle(),i=c.getMap();s?(y.hide(),this.clusterLayer&&i.removeLayer(this.clusterLayer)):y.isVisible||this.clusterLayer&&i.addLayer(this.clusterLayer),e.classList.toggle("active",s),(a=document.getElementById("hexagonToggle"))==null||a.classList.remove("active")});const t=document.getElementById("hexagonToggle");t&&t.addEventListener("click",()=>{var a;const s=y.toggle(),i=c.getMap();s?(f.hide(),this.clusterLayer&&i.removeLayer(this.clusterLayer)):f.isVisible||this.clusterLayer&&i.addLayer(this.clusterLayer),t.classList.toggle("active",s),(a=document.getElementById("heatmapToggle"))==null||a.classList.remove("active")})}async loadPoints(){try{console.log("Iniciando carga de datos...");const e=await fetch("https://api.metrixmobile.xyz/api/incidencias");if(!e.ok)throw new Error(`Error HTTP: ${e.status}`);const t=await e.json();if(!t||!t.data||!Array.isArray(t.data))throw new Error("Formato de respuesta inválido");const s=t.data.filter(i=>U.validatePoint(i));return s.length===0?(console.warn("No se encontraron puntos válidos en la respuesta"),[]):(console.log(`Datos recibidos: ${s.length} puntos válidos`),this.handlePoints(s),f.setData(s),y.setData(s),v.registerIncidentsLayer(this.clusterLayer),s)}catch(e){throw console.error("Error al cargar datos:",e),this.handlePoints([]),e}}handlePoints(e){const t=c.getMap();if(!t){console.error("Mapa no inicializado");return}this.pointsLayer&&t.removeLayer(this.pointsLayer),this.clusterLayer&&t.removeLayer(this.clusterLayer);const s={type:"FeatureCollection",features:e.filter(i=>{const a=parseFloat(i.latitud),o=parseFloat(i.longitud);return!isNaN(a)&&!isNaN(o)}).map(i=>({type:"Feature",geometry:{type:"Point",coordinates:[parseFloat(i.longitud),parseFloat(i.latitud)]},properties:i}))};this.points=s.features,this.totalPoints=this.points.length,this.updateVisiblePoints(),this.clusterLayer=L.markerClusterGroup({disableClusteringAtZoom:13,spiderfyOnMaxZoom:!0,showCoverageOnHover:!0,zoomToBoundsOnClick:!0,maxClusterRadius:50,iconCreateFunction:i=>{const a=i.getChildCount();let o="small";return a>50?o="large":a>20&&(o="medium"),L.divIcon({html:`<div><span>${a}</span></div>`,className:`marker-cluster marker-cluster-${o}`,iconSize:L.point(40,40)})}}),this.pointsLayer=L.geoJSON(s,{pointToLayer:(i,a)=>{var l;const o=((l=i.properties.estado)==null?void 0:l.toLowerCase())||"abierto",r=i.properties.color_sla||"#ef4444";return L.circleMarker(a,{radius:8,fillColor:r,color:"#ffffff",weight:1,opacity:1,fillOpacity:o==="abierto"?.8:.4})},onEachFeature:(i,a)=>{const o=R.createPopupContent(i.properties);a.bindPopup(o,{maxWidth:300,maxHeight:400,autoPan:!0,closeButton:!0,autoPanPadding:[40,40]})}}),this.clusterLayer.addLayer(this.pointsLayer),t.addLayer(this.clusterLayer),t.on("moveend",()=>this.updateVisiblePoints()),t.on("zoomend",()=>this.updateVisiblePoints()),console.log("Capas de puntos agregadas al mapa")}updateVisiblePoints(){const e=c.getMap();if(!e||!this.points)return;const t=e.getBounds();this.visiblePointsCount=this.points.filter(s=>{const i=s.geometry.coordinates;return t.contains([i[1],i[0]])}).length,this.updatePointsCounter()}updatePointsCounter(){const e=document.getElementById("pointsCounter");e&&(e.textContent=`${this.visiblePointsCount} / ${this.totalPoints}`)}filterPoints(e){if(!e||!this.points)return;const t=turf.pointsWithinPolygon({type:"FeatureCollection",features:this.points},e);return this.visiblePointsCount=t.features.length,this.updatePointsCounter(),t.features}}const p=new Y,H=document.createElement("style");H.textContent=`
    .marker-cluster {
        background-clip: padding-box;
        border-radius: 20px;
        background-color: rgba(239, 68, 68, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        border: 2px solid white;
    }

    .marker-cluster div {
        width: 30px;
        height: 30px;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        border-radius: 15px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(239, 68, 68, 0.8);
    }

    .marker-cluster-small {
        background-color: rgba(239, 68, 68, 0.6);
    }

    .marker-cluster-medium {
        background-color: rgba(239, 68, 68, 0.7);
    }

    .marker-cluster-large {
        background-color: rgba(239, 68, 68, 0.8);
    }

    .control-btn.active {
        background-color: var(--primary-color);
        color: white;
    }
`;document.head.appendChild(H);const u={baseUrl:"https://espacialhn.com/slim4/api",endpoints:{states:{get:"/states",getById:n=>`/states/${n}`,create:"/states",delete:n=>`/states/${n}`},points:{get:"/points",getByState:n=>`/states/${n}/points`}}};class Q{async getAllStates(){try{const e=await fetch(`${u.baseUrl}${u.endpoints.states.get}`);if(!e.ok)throw new Error(`HTTP error! status: ${e.status}`);return await e.json()}catch(e){throw console.error("Error fetching states:",e),e}}async getStateById(e){try{const t=await fetch(`${u.baseUrl}${u.endpoints.states.getById(e)}`);if(!t.ok)throw new Error(`HTTP error! status: ${t.status}`);return await t.json()}catch(t){throw console.error("Error fetching state:",t),t}}async createState(e){try{const t=await fetch(`${u.baseUrl}${u.endpoints.states.create}`,{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(e)});if(!t.ok)throw new Error(`HTTP error! status: ${t.status}`);return await t.json()}catch(t){throw console.error("Error creating state:",t),t}}async deleteState(e){try{const t=await fetch(`${u.baseUrl}${u.endpoints.states.delete(e)}`,{method:"DELETE"});if(!t.ok)throw new Error(`HTTP error! status: ${t.status}`);return await t.json()}catch(t){throw console.error("Error deleting state:",t),t}}}const w=new Q;class X{async getAllPoints(){try{const e=await fetch(`${u.baseUrl}${u.endpoints.points.get}`);if(!e.ok)throw new Error(`HTTP error! status: ${e.status}`);return await e.json()}catch(e){throw console.error("Error fetching points:",e),e}}async getPointsByState(e){try{const t=await fetch(`${u.baseUrl}${u.endpoints.points.getByState(e)}`);if(!t.ok)throw new Error(`HTTP error! status: ${t.status}`);return await t.json()}catch(t){throw console.error("Error fetching points for state:",t),t}}}const ee=new X;class te{constructor(){this.currentState=null,this.statePrefix="MAP_"}generateSessionId(){const t=new Date().toISOString().replace(/[-:]/g,"").replace("T","_").split(".")[0];return`${this.statePrefix}${t}`}async saveCurrentState(e={}){const t=c.getMap();if(!t||!p.points)return null;const s={sessionId:this.generateSessionId(),timestamp:new Date().toISOString(),filterCriteria:e,points:p.points.map(i=>i.properties.identificador),viewport:{center:t.getCenter(),zoom:t.getZoom()}};try{return localStorage.setItem(s.sessionId,JSON.stringify(s)),await w.createState(s),this.currentState=s,s.sessionId}catch(i){return console.error("Error saving map state:",i),null}}async loadState(e){try{let t=null;const s=localStorage.getItem(e);if(s?t=JSON.parse(s):(t=await w.getStateById(e),t&&localStorage.setItem(e,JSON.stringify(t))),!t)return!1;this.currentState=t;const i=c.getMap();if(i&&t.viewport&&i.setView([t.viewport.center.lat,t.viewport.center.lng],t.viewport.zoom),t.points&&t.points.length>0){const a=await ee.getPointsByState(e);if(a&&a.length>0)p.handlePoints(a);else{const o=p.points.filter(r=>t.points.includes(r.properties.identificador));p.handlePoints(o.map(r=>r.properties))}}return!0}catch(t){return console.error("Error loading map state:",t),!1}}async getAllStates(){try{const e=await w.getAllStates(),t=[];for(let a=0;a<localStorage.length;a++){const o=localStorage.key(a);if(o.startsWith(this.statePrefix))try{const r=JSON.parse(localStorage.getItem(o));t.push(r)}catch(r){console.error(`Error parsing local state ${o}:`,r)}}const s=[...e,...t];return Array.from(new Map(s.map(a=>[a.sessionId,a])).values()).sort((a,o)=>new Date(o.timestamp)-new Date(a.timestamp))}catch(e){return console.error("Error getting all states:",e),this.getLocalStates()}}getLocalStates(){const e=[];for(let t=0;t<localStorage.length;t++){const s=localStorage.key(t);if(s.startsWith(this.statePrefix))try{const i=JSON.parse(localStorage.getItem(s));e.push(i)}catch(i){console.error(`Error parsing state ${s}:`,i)}}return e.sort((t,s)=>new Date(s.timestamp)-new Date(t.timestamp))}async clearState(e){var t;try{return localStorage.removeItem(e),await w.deleteState(e),((t=this.currentState)==null?void 0:t.sessionId)===e&&(this.currentState=null),!0}catch(s){return console.error("Error clearing state:",s),!1}}getCurrentState(){return this.currentState}}const g=new te;class se{constructor(e){this.map=e,this.drawnItems=new L.FeatureGroup,this.setupDrawControl(),this.setupClearFilterButton(),this.isFiltering=!1}setupDrawControl(){this.map.addLayer(this.drawnItems);const e=new L.Control.Draw({position:"topright",draw:{marker:!1,circle:!1,circlemarker:!1,rectangle:{shapeOptions:{color:"#00A650",weight:2}},polygon:{shapeOptions:{color:"#00A650",weight:2}},polyline:!1},edit:{featureGroup:this.drawnItems,remove:!0}});this.map.addControl(e),this.map.on(L.Draw.Event.CREATED,t=>{const s=t.layer;this.drawnItems.clearLayers(),this.drawnItems.addLayer(s);const i=s.toGeoJSON().geometry;this.filterPoints(i)}),this.map.on(L.Draw.Event.DELETED,()=>{this.showAllPoints()})}setupClearFilterButton(){const e=L.Control.extend({options:{position:"topright"},onAdd:()=>{const t=L.DomUtil.create("div","leaflet-bar leaflet-control"),s=L.DomUtil.create("a","clear-filter-button",t);return s.innerHTML="×",s.href="#",s.title="Limpiar Filtro",L.DomEvent.on(s,"click",i=>{L.DomEvent.preventDefault(i),this.showAllPoints()}),t}});this.clearFilterControl=new e,this.map.addControl(this.clearFilterControl)}filterPoints(e){if(e)try{this.isFiltering=!0,console.log("Filtrando puntos dentro del polígono...");const t=p.filterPoints(e);if(!t||t.length===0){console.warn("No se encontraron puntos dentro del polígono");return}console.log(`Encontrados ${t.length} puntos dentro del polígono`),p.handlePoints(t.map(s=>s.properties)),g.saveCurrentState({type:"polygon",geometry:e})}catch(t){console.error("Error al filtrar puntos:",t),this.showAllPoints()}}showAllPoints(){this.isFiltering&&(console.log("Mostrando todos los puntos..."),this.drawnItems.clearLayers(),this.isFiltering=!1,p.loadPoints(),g.saveCurrentState({}))}}let E=null;function ie(n){return E||(E=new se(n)),E}class ae{constructor(e){this.map=e,this.initialize()}initialize(){console.log("Initializing WFS layers..."),v.init(this.map),v.loadAllLayers()}}let S=null;function oe(n){return S||(S=new ae(n)),S}class ne{constructor(){this.init()}init(){const e=localStorage.getItem("theme"),t=window.matchMedia("(prefers-color-scheme: dark)").matches,s=e||(t?"dark":"light");this.setTheme(s);const i=document.getElementById("themeToggle");i&&(i.addEventListener("click",()=>this.toggleTheme()),this.updateThemeButton(s)),window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change",a=>{localStorage.getItem("theme")||this.setTheme(a.matches?"dark":"light")})}setTheme(e){document.body.dataset.theme=e,localStorage.setItem("theme",e),this.updateThemeButton(e)}toggleTheme(){const t=document.body.dataset.theme==="dark"?"light":"dark";this.setTheme(t)}updateThemeButton(e){const t=document.getElementById("themeToggle");if(t){const s=t.querySelector(".icon");s&&(s.textContent=e==="dark"?"☀️":"🌙")}}getCurrentTheme(){return document.body.dataset.theme||"light"}}new ne;class re{constructor(){this.filters={prioridad:"",estado:"",fechaInicio:"",fechaFin:"",keyword:""},this.searchFields=["identificador","titulo","descripcion","direccion_completa"],this.activeFiltersCount=0,this.filterBar=null,this.init()}init(){document.getElementById("filterToggle")||this.createFilterToggle(),this.createFilterBar(),this.setupEventListeners()}createFilterToggle(){const e=document.createElement("button");e.id="filterToggle",e.className="control-btn",e.title="Filtrar Puntos",e.innerHTML=`
            <i class="bi bi-funnel"></i>
            <span class="filter-badge">0</span>
        `;const t=document.querySelector(".nav-content .flex.items-center.gap-4");t&&t.insertBefore(e,t.firstChild)}createFilterBar(){const e=document.querySelector(".filter-bar");e&&e.remove(),this.filterBar=document.createElement("div"),this.filterBar.className="filter-bar",this.filterBar.innerHTML=`
            <div class="filter-container">
                <div class="filter-sidebar">
                    <div class="filter-header">
                        <div class="filter-header-content">
                            <h3>Filtros</h3>
                            <span class="filter-applied">0 aplicados</span>
                        </div>
                        <button class="filter-close">×</button>
                    </div>
                    <div class="priority-buttons">
                        <button class="priority-button" data-priority="Alta">Alta</button>
                        <button class="priority-button" data-priority="Media">Media</button>
                        <button class="priority-button" data-priority="Baja">Baja</button>
                    </div>
                </div>
                <div class="filter-main">
                    <div class="filter-content">
                        <div class="filter-section">
                            <label class="filter-label">Buscar por palabra clave</label>
                            <input type="text" class="filter-input" id="keywordFilter" 
                                   placeholder="Buscar en identificador, título, descripción o dirección...">
                        </div>
                        <div class="filter-section">
                            <label class="filter-label">Estado</label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="Abierto">
                                    <span>Abierto</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="En Proceso">
                                    <span>En Proceso</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="estado" value="Cerrado">
                                    <span>Cerrado</span>
                                </label>
                            </div>
                        </div>
                        <div class="filter-section">
                            <label class="filter-label">Rango de Fechas</label>
                            <div class="date-inputs">
                                <input type="date" id="fechaInicioFilter" placeholder="Desde">
                                <input type="date" id="fechaFinFilter" placeholder="Hasta">
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button class="filter-btn filter-btn-secondary" id="clearFilters">
                                Limpiar filtros
                            </button>
                            <button class="filter-btn filter-btn-primary" id="applyFilters">
                                Aplicar filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `,document.body.appendChild(this.filterBar)}setupEventListeners(){const e=document.getElementById("filterToggle"),t=this.filterBar.querySelector(".filter-close"),s=this.filterBar.querySelector("#applyFilters"),i=this.filterBar.querySelector("#clearFilters"),a=this.filterBar.querySelector("#keywordFilter"),o=this.filterBar.querySelectorAll(".priority-button");e==null||e.addEventListener("click",()=>{this.filterBar.classList.toggle("active")}),t==null||t.addEventListener("click",()=>{this.filterBar.classList.remove("active")}),s==null||s.addEventListener("click",()=>this.applyFilters()),i==null||i.addEventListener("click",()=>this.clearFilters()),a==null||a.addEventListener("input",r=>{(r.target.value.length>=3||r.target.value.length===0)&&(this.filters.keyword=r.target.value,this.applyFilters())}),o.forEach(r=>{r.addEventListener("click",()=>{o.forEach(l=>l.classList.remove("active")),r.classList.add("active"),this.filters.prioridad=r.dataset.priority,this.applyFilters()})}),document.addEventListener("click",r=>{!this.filterBar.contains(r.target)&&!(e!=null&&e.contains(r.target))&&this.filterBar.classList.remove("active")})}applyFilters(){var e;this.filters={keyword:document.getElementById("keywordFilter").value,prioridad:this.filters.prioridad,estado:((e=document.querySelector('input[name="estado"]:checked'))==null?void 0:e.value)||"",fechaInicio:document.getElementById("fechaInicioFilter").value,fechaFin:document.getElementById("fechaFinFilter").value},this.updateActiveFiltersCount(),this.filterPoints(),document.dispatchEvent(new CustomEvent("filterApplied"))}clearFilters(){document.getElementById("keywordFilter").value="",document.querySelectorAll(".priority-button").forEach(t=>t.classList.remove("active")),document.querySelectorAll('input[name="estado"]').forEach(t=>t.checked=!1),document.getElementById("fechaInicioFilter").value="",document.getElementById("fechaFinFilter").value="",this.filters={keyword:"",prioridad:"",estado:"",fechaInicio:"",fechaFin:""},this.updateActiveFiltersCount();const{dataLoader:e}=window;e&&e.loadPoints(),this.filterBar.classList.remove("active"),document.dispatchEvent(new CustomEvent("filterCleared"))}updateActiveFiltersCount(){this.activeFiltersCount=Object.values(this.filters).filter(s=>s!=="").length;const e=document.querySelector(".filter-badge"),t=this.filterBar.querySelector(".filter-applied");this.activeFiltersCount>0?(e.textContent=this.activeFiltersCount,e.classList.add("active"),t.textContent=`${this.activeFiltersCount} aplicados`):(e.textContent="0",e.classList.remove("active"),t.textContent="Sin filtros")}filterPoints(){const{dataLoader:e}=window;if(!e||!e.points)return;const t=e.points.filter(s=>{const i=s.properties;if(this.filters.keyword){const a=this.filters.keyword.toLowerCase();if(!this.searchFields.some(r=>String(i[r]||"").toLowerCase().includes(a)))return!1}if(this.filters.prioridad&&i.prioridad!==this.filters.prioridad||this.filters.estado&&i.estado!==this.filters.estado)return!1;if(this.filters.fechaInicio||this.filters.fechaFin){const a=new Date(i.fecha_creacion);if(this.filters.fechaInicio){const o=new Date(this.filters.fechaInicio);if(a<o)return!1}if(this.filters.fechaFin){const o=new Date(this.filters.fechaFin);if(a>o)return!1}}return!0});e.handlePoints(t.map(s=>s.properties))}}class le{constructor(){this.container=null,this.isVisible=!1,this.init()}init(){this.createToggleButton(),this.createContainer(),this.setupEventListeners(),this.update()}createToggleButton(){const e=document.createElement("div");e.className="footer-section";const t=document.createElement("button");t.className="symbology-toggle",t.innerHTML=`
            <i class="bi bi-chevron-left"></i>
            <span>Simbología</span>
        `,e.appendChild(t),document.querySelector(".footer-content").appendChild(e)}createContainer(){this.container=document.createElement("div"),this.container.className="symbology-container",this.container.innerHTML=`
            <div class="symbology-header">
                <h3>Simbología del Mapa</h3>
            </div>
            <div class="symbology-content">
                <table class="symbology-table">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Símbolo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        `,document.body.appendChild(this.container)}setupEventListeners(){const e=document.querySelector(".symbology-toggle");e.addEventListener("click",()=>{this.toggle(),e.classList.toggle("active")}),document.addEventListener("filterApplied",()=>this.update()),document.addEventListener("filterCleared",()=>this.update())}toggle(){this.isVisible?this.hide():this.show()}show(){this.container.classList.add("active"),this.isVisible=!0,this.update()}hide(){this.container.classList.remove("active"),this.isVisible=!1}update(){const e=p.points,t={Abierto:{count:0,color:"#FFA500"},"En Proceso":{count:0,color:"#3B82F6"},Cerrado:{count:0,color:"#10B981"}};e.forEach(i=>{const a=i.properties.estado;t[a]&&t[a].count++});const s=this.container.querySelector("tbody");s.innerHTML=Object.entries(t).map(([i,a])=>`
                <tr>
                    <td>${i}</td>
                    <td>
                        <span class="symbol-icon" style="background-color: ${a.color}"></span>
                    </td>
                    <td class="symbol-count">${a.count}</td>
                </tr>
            `).join("")}}class ce{constructor(){this.isExporting=!1,this.init()}init(){this.createExportButton(),this.setupEventListeners()}createExportButton(){const e=document.createElement("button");e.id="exportMap",e.className="control-btn",e.title="Exportar Mapa",e.innerHTML=`
            <i class="bi bi-download"></i>
        `;const t=document.getElementById("filterToggle");t&&t.parentNode&&t.parentNode.insertBefore(e,t)}setupEventListeners(){const e=document.getElementById("exportMap");e&&e.addEventListener("click",()=>this.exportMap())}async exportMap(){if(!this.isExporting)try{this.isExporting=!0;const e=document.getElementById("exportMap");if(e.classList.add("loading"),e.innerHTML='<i class="bi bi-arrow-clockwise"></i>',!c.getMap())throw new Error("Map not initialized");const s=document.getElementById("map");(await window.html2canvas(s,{useCORS:!0,allowTaint:!0,foreignObjectRendering:!0,logging:!1,scale:2,backgroundColor:null})).toBlob(a=>{if(!a)throw new Error("Failed to create blob");const r=`mapa_${new Date().toISOString().split("T")[0]}.png`;window.saveAs(a,r),e.classList.remove("loading"),e.innerHTML='<i class="bi bi-download"></i>',this.isExporting=!1},"image/png")}catch(e){console.error("Error exporting map:",e);const t=document.getElementById("exportMap");t.classList.remove("loading"),t.innerHTML='<i class="bi bi-download"></i>',this.isExporting=!1}}}class de{constructor(){this.init()}init(){this.createExportButton(),this.setupEventListeners()}createExportButton(){const e=document.createElement("button");e.id="exportPoints",e.className="control-btn",e.title="Exportar Puntos CSV",e.innerHTML=`
            <i class="bi bi-file-earmark-spreadsheet"></i>
        `;const t=document.getElementById("exportMap");t&&t.parentNode&&t.parentNode.insertBefore(e,t.nextSibling)}setupEventListeners(){const e=document.getElementById("exportPoints");e&&e.addEventListener("click",()=>this.exportPoints())}exportPoints(){try{const e=p.points;if(!e||e.length===0){console.warn("No hay puntos para exportar");return}const t=["identificador","titulo","descripcion","prioridad","latitud","longitud","estado","municipio","colonia","codigo_postal","direccion_completa","fecha_creacion","fecha_vencimiento","nombre_cliente","nombre_area"],s=[t.join(","),...e.map(r=>{const l=r.properties;return t.map(d=>{const m=l[d];return m==null?"":`"${String(m).replace(/"/g,'""')}"`}).join(",")})].join(`
`),i=new Blob([s],{type:"text/csv;charset=utf-8;"}),o=`puntos_${new Date().toISOString().split("T")[0]}.csv`;window.saveAs(i,o)}catch(e){console.error("Error exportando puntos:",e)}}}class pe{constructor(){this.init()}init(){const e=document.createElement("button");e.id="view3DButton",e.className="control-btn",e.title="Ver en 3D",e.innerHTML='<i class="bi bi-badge-3d"></i>';const t=document.getElementById("exportPoints");t&&t.parentNode&&t.parentNode.insertBefore(e,t.nextSibling),this.setupEventListeners(e)}setupEventListeners(e){e.addEventListener("click",()=>this.open3DView())}open3DView(){const e=window.dataLoader;if(!e||!e.points)return;const t={type:"FeatureCollection",features:e.points.map(s=>({type:"Feature",geometry:{type:"Point",coordinates:[parseFloat(s.properties.longitud),parseFloat(s.properties.latitud)]},properties:s.properties}))};sessionStorage.setItem("metrix3DPoints",JSON.stringify(t)),window.open("./maptiler3d.html","_blank")}}class ue{constructor(){this.container=null,this.isVisible=!1,this.init()}init(){this.createToggleButton(),this.createContainer(),this.setupEventListeners(),this.update()}createToggleButton(){const e=document.createElement("div");e.className="footer-section";const t=document.createElement("button");t.className="dashboard-toggle",t.innerHTML=`
            <i class="bi bi-graph-up"></i>
            <span>Informe</span>
        `,e.appendChild(t),document.querySelector(".footer-content").appendChild(e)}createContainer(){this.container=document.createElement("div"),this.container.className="dashboard-container",this.container.innerHTML=`
            <div class="dashboard-header">
                <h3>Dashboard de Incidencias</h3>
                <button class="control-btn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="dashboard-content">
                <div class="stat-card">
                    <h4>Total de Incidencias</h4>
                    <div class="stat-value" id="totalIncidencias">0</div>
                </div>

                <div class="stat-grid">
                    <div class="mini-stat">
                        <div class="value" id="incidenciasAbiertas">0</div>
                        <div class="label">Abiertas</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasProceso">0</div>
                        <div class="label">En Proceso</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasCerradas">0</div>
                        <div class="label">Cerradas</div>
                    </div>
                    <div class="mini-stat">
                        <div class="value" id="incidenciasHoy">0</div>
                        <div class="label">Hoy</div>
                    </div>
                </div>

                <div class="stat-card">
                    <h4>Por Prioridad</h4>
                    <div class="stat-list">
                        <div class="stat-item">
                            <span class="label">Alta</span>
                            <span class="value" id="prioridadAlta">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Media</span>
                            <span class="value" id="prioridadMedia">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Baja</span>
                            <span class="value" id="prioridadBaja">0</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <h4>Estadísticas Temporales</h4>
                    <div class="stat-list">
                        <div class="stat-item">
                            <span class="label">Esta Semana</span>
                            <span class="value" id="estaSemana">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Este Mes</span>
                            <span class="value" id="esteMes">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Vencidas</span>
                            <span class="value" id="vencidas">0</span>
                        </div>
                    </div>
                </div>
            </div>
        `,document.body.appendChild(this.container)}setupEventListeners(){const e=document.querySelector(".dashboard-toggle"),t=this.container.querySelector(".control-btn");e.addEventListener("click",()=>{this.toggle(),e.classList.toggle("active")}),t.addEventListener("click",()=>this.hide()),document.addEventListener("filterApplied",()=>this.update()),document.addEventListener("filterCleared",()=>this.update())}toggle(){this.isVisible?this.hide():this.show()}show(){this.container.classList.add("active"),this.isVisible=!0,this.update()}hide(){this.container.classList.remove("active"),this.isVisible=!1}update(){const e=p.points;if(!e)return;const t=new Date,s=new Date(t.getFullYear(),t.getMonth(),t.getDate()),i=new Date(s);i.setDate(s.getDate()-s.getDay());const a=new Date(t.getFullYear(),t.getMonth(),1),o={total:e.length,estados:{Abierto:0,"En Proceso":0,Cerrado:0},prioridad:{Alta:0,Media:0,Baja:0},temporal:{hoy:0,semana:0,mes:0,vencidas:0}};e.forEach(r=>{const l=r.properties;o.estados[l.estado]=(o.estados[l.estado]||0)+1,o.prioridad[l.prioridad]=(o.prioridad[l.prioridad]||0)+1;const d=new Date(l.fecha_creacion);d>=s&&o.temporal.hoy++,d>=i&&o.temporal.semana++,d>=a&&o.temporal.mes++,l.fecha_vencimiento&&new Date(l.fecha_vencimiento)<t&&l.estado!=="Cerrado"&&o.temporal.vencidas++}),document.getElementById("totalIncidencias").textContent=o.total,document.getElementById("incidenciasAbiertas").textContent=o.estados.Abierto,document.getElementById("incidenciasProceso").textContent=o.estados["En Proceso"],document.getElementById("incidenciasCerradas").textContent=o.estados.Cerrado,document.getElementById("incidenciasHoy").textContent=o.temporal.hoy,document.getElementById("prioridadAlta").textContent=o.prioridad.Alta,document.getElementById("prioridadMedia").textContent=o.prioridad.Media,document.getElementById("prioridadBaja").textContent=o.prioridad.Baja,document.getElementById("estaSemana").textContent=o.temporal.semana,document.getElementById("esteMes").textContent=o.temporal.mes,document.getElementById("vencidas").textContent=o.temporal.vencidas}}class he{constructor(){this.init()}init(){const e=document.createElement("div");e.className="footer-section";const t=document.createElement("button");t.className="state-toggle",t.innerHTML=`
            <i class="bi bi-share"></i>
            <span>Compartir</span>
        `,e.appendChild(t),document.querySelector(".footer-content").appendChild(e),this.setupEventListeners(t)}setupEventListeners(e){e.addEventListener("click",async()=>{await this.showStateDialog()}),document.addEventListener("filterApplied",t=>{t.detail&&t.detail.filters&&g.saveCurrentState(t.detail.filters)})}async showStateDialog(){try{const e=await g.getLocalStates(),t=document.createElement("div");t.className="modal-overlay active",t.innerHTML=`
                <div class="modal-container">
                    <div class="modal-header">
                        <h2>Estados Guardados</h2>
                        <button class="modal-close">×</button>
                    </div>
                    <div class="modal-content">
                        <div class="p-4">
                            ${e.length===0?`
                                <div class="text-center text-gray-500 py-4">
                                    No hay estados guardados
                                </div>
                            `:`
                                <div class="states-list">
                                    ${e.map(i=>`
                                        <div class="state-item">
                                            <div class="state-info">
                                                <div class="state-id">${i.sessionId}</div>
                                                <div class="state-time">${new Date(i.timestamp).toLocaleString()}</div>
                                                <div class="state-points">Puntos: ${i.points.length}</div>
                                            </div>
                                            <div class="state-actions">
                                                <button onclick="window.loadMapState('${i.sessionId}')" class="modal-btn-secondary">
                                                    Cargar
                                                </button>
                                                <button onclick="window.shareMapState('${i.sessionId}')" class="modal-btn-primary">
                                                    Compartir
                                                </button>
                                            </div>
                                        </div>
                                    `).join("")}
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `,document.body.appendChild(t),t.querySelector(".modal-close").addEventListener("click",()=>t.remove()),t.addEventListener("click",i=>{i.target===t&&t.remove()}),window.loadMapState=async i=>{await g.loadState(i),t.remove()},window.shareMapState=i=>{const a=`${window.location.origin}${window.location.pathname}?state=${i}`;navigator.clipboard.writeText(a),alert("URL copiada al portapapeles")}}catch(e){console.error("Error showing state dialog:",e),alert("Error al cargar los estados guardados")}}}const h=c.initialize("map",{zoomControl:!1,fullscreenControl:!0,fullscreenControlOptions:{position:"topright"}});f.map=h;y.setMap(h);new re;new le;new ce;new de;new pe;new ue;new he;window.dataLoader=p;const me=new URLSearchParams(window.location.search),F=me.get("state");F&&g.loadState(F);const ge=document.querySelector(".footer-section:nth-child(2)"),N=document.querySelector(".coord-input");ge.addEventListener("click",n=>{n.target.closest(".coord-input")||N.classList.toggle("active")});document.getElementById("goToCoord").addEventListener("click",()=>{const e=document.getElementById("coordInput").value.split(",").map(t=>parseFloat(t.trim()));e.length===2&&!isNaN(e[0])&&!isNaN(e[1])&&(h.setView(e,h.getZoom()),N.classList.remove("active"))});document.getElementById("coordInput").addEventListener("keypress",n=>{n.key==="Enter"&&document.getElementById("goToCoord").click()});function M(n){const e=h.getZoom();document.getElementById("currentZoom").textContent=e;const t=Math.round(e*1e5);if(document.getElementById("mapScale").textContent=`1:${t}`,n&&n.latlng){const{lat:s,lng:i}=n.latlng;document.getElementById("currentCoords").textContent=`${s.toFixed(6)}, ${i.toFixed(6)}`}e>=10?c.switchToSatellite():c.switchToDefault()}h.on("zoomend",M);h.on("mousemove",M);h.on("moveend",M);document.addEventListener("DOMContentLoaded",async()=>{try{ie(h),oe(h),console.log("Iniciando carga de datos..."),await p.loadPoints(),console.log("Inicialización completa")}catch(n){console.error("Error durante la inicialización:",n)}});L.control.polylineMeasure({position:"topright",unit:"metres",showBearings:!0,clearMeasurementsOnStop:!1,showClearControl:!1,showUnitControl:!1}).addTo(h);class _{constructor(e={}){this.options={size:"md",title:"",subtitle:"",content:"",onClose:null,onConfirm:null,showFooter:!1,showHeader:!0,...e},this.create(),this.setupEventListeners()}getModalSize(){const e={sm:"max-w-lg",md:"max-w-2xl",lg:"max-w-4xl",xl:"max-w-6xl"};return e[this.options.size]||e.md}create(){this.overlay=document.createElement("div"),this.overlay.className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-start justify-center pt-10 z-[2000] hidden";let e=this.options.showHeader?`
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">${this.options.title}</h2>
                    ${this.options.subtitle?`<div class="text-sm text-emerald-600 font-mono mt-1">${this.options.subtitle}</div>`:""}
                </div>
                <button class="text-3xl text-gray-400 hover:text-gray-600 transition-colors">×</button>
            </div>
        `:"",t=this.options.showFooter?`
            <div class="border-t border-gray-100 p-4 bg-white dark:bg-gray-800">
                <div class="flex justify-end">
                    <button class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        `:"";const s=`
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl ${this.getModalSize()} w-full mx-4 overflow-hidden">
                ${e}
                <div class="overflow-y-auto max-h-[calc(90vh-8rem)]">
                    ${this.options.content}
                </div>
                ${t}
            </div>
        `;this.overlay.innerHTML=s,document.body.appendChild(this.overlay)}setupEventListeners(){const e=this.overlay.querySelector("button"),t=this.overlay.querySelector(".bg-emerald-600");e&&e.addEventListener("click",()=>this.close()),t&&t.addEventListener("click",()=>this.close()),this.overlay.addEventListener("click",s=>{s.target===this.overlay&&this.close()})}open(){this.overlay.classList.remove("hidden")}close(){this.overlay.classList.add("hidden"),setTimeout(()=>this.destroy(),200),this.options.onClose&&this.options.onClose()}destroy(){this.overlay.remove()}}window.showIncidentDetails=n=>{const e=new Date(n.fecha_creacion).toLocaleString(),t=n.fecha_vencimiento?new Date(n.fecha_vencimiento).toLocaleString():"No establecida";new _({size:"md",title:"Información del Ticket",subtitle:`#${n.identificador}`,content:`
            <div class="p-6 space-y-8">
                <!-- Información General -->
                <div>
                    <h3 class="text-lg font-medium text-emerald-700 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Información General
                    </h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Estado</div>
                            <span class="px-3 py-1 inline-flex text-sm font-medium rounded-full
                                ${n.estado.toLowerCase()==="abierto"?"bg-yellow-50 text-yellow-800 border border-yellow-200":"bg-emerald-50 text-emerald-800 border border-emerald-200"}">
                                ${n.estado}
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Prioridad</div>
                            <span class="px-3 py-1 inline-flex text-sm font-medium rounded-full
                                ${n.prioridad==="Alto"?"bg-red-50 text-red-800 border border-red-200":n.prioridad==="Medio"?"bg-orange-50 text-orange-800 border border-orange-200":"bg-emerald-50 text-emerald-800 border border-emerald-200"}">
                                ${n.prioridad}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center gap-1 text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Fecha de Creación
                        </div>
                        <div class="text-sm font-medium">${e}</div>
                    </div>
                    <div>
                        <div class="flex items-center gap-1 text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Fecha de Vencimiento
                        </div>
                        <div class="text-sm font-medium ${t==="No establecida"?"text-gray-400":""}">${t}</div>
                    </div>
                </div>

                <!-- Detalles -->
                <div>
                    <h3 class="text-lg font-medium text-emerald-700 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Detalles
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Cliente</div>
                            <div class="font-medium">${n.nombre_cliente}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Área</div>
                            <div class="font-medium">${n.nombre_area}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Título</div>
                            <div class="font-medium">${n.titulo}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Descripción</div>
                            <div class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                ${n.descripcion}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Ubicación</div>
                            <div class="text-sm text-gray-600">
                                ${n.direccion_completa}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `}).open()};const fe=()=>`
    <div class="filters-content">
        <div class="filters-section">
            <div class="filters-header">
                <h3>Filters</h3>
                <span class="applied-filters">3 applied <span class="remove">×</span></span>
            </div>
            
            <div class="filter-group">
                <button class="filter-button">
                    <span>Company name</span>
                    <span>›</span>
                </button>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Location</span>
                    <span>›</span>
                </button>
                <div class="filter-tags">
                    <span class="filter-tag">Berlin <span class="remove">×</span></span>
                    <span class="filter-tag">France <span class="remove">×</span></span>
                </div>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Industry</span>
                    <span>›</span>
                </button>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Employee headcount</span>
                    <span>›</span>
                </button>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Revenue</span>
                    <span>›</span>
                </button>
                <div class="filter-tags">
                    <span class="filter-tag">1mil <span class="remove">×</span></span>
                </div>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Funding</span>
                    <span>›</span>
                </button>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Technology</span>
                    <span>›</span>
                </button>
            </div>

            <div class="filter-group">
                <button class="filter-button">
                    <span>Year founded</span>
                    <span>›</span>
                </button>
                <div class="filter-tags">
                    <span class="filter-tag">2024 <span class="remove">×</span></span>
                    <span class="filter-tag">2023 <span class="remove">×</span></span>
                </div>
            </div>
        </div>

        <div class="funding-section">
            <h3>Select funding date and type</h3>
            
            <div class="funding-options">
                <label class="funding-option selected">
                    <input type="radio" name="funding" checked>
                    <span>Any round</span>
                </label>
                <label class="funding-option">
                    <input type="radio" name="funding">
                    <span>Last round</span>
                </label>
            </div>

            <div class="funding-range">
                <label>Funding date</label>
                <div class="range-input">
                    <select>
                        <option>All times</option>
                    </select>
                </div>
            </div>

            <div class="advanced-section">
                <button class="advanced-toggle">
                    <span>▼</span> Advanced
                </button>

                <div class="funding-range">
                    <label>Select last funding amount</label>
                    <div class="range-inputs">
                        <div class="range-input">
                            <select>
                                <option>$ Min</option>
                            </select>
                        </div>
                        <span>to</span>
                        <div class="range-input">
                            <select>
                                <option>$ Max</option>
                            </select>
                        </div>
                    </div>
                    <button class="advanced-toggle">Apply</button>
                </div>

                <div class="funding-range">
                    <label>Select total funding amount</label>
                    <div class="range-inputs">
                        <div class="range-input">
                            <select>
                                <option>$ Min</option>
                            </select>
                        </div>
                        <span>to</span>
                        <div class="range-input">
                            <select>
                                <option>$ Max</option>
                            </select>
                        </div>
                    </div>
                    <button class="advanced-toggle">Apply</button>
                </div>
            </div>
        </div>
    </div>
`,ye=()=>`
    <div class="demo-content">
        <h3>Medium Modal Demo</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        <div class="demo-section">
            <h4>Features</h4>
            <ul>
                <li>Responsive design</li>
                <li>Customizable content</li>
                <li>Flexible layout</li>
            </ul>
        </div>
    </div>
`,ve=()=>`
    <div class="demo-content">
        <h3>Large Modal Demo</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        <div class="demo-section">
            <h4>Extended Features</h4>
            <div class="feature-grid">
                <div class="feature-item">
                    <h5>Analytics</h5>
                    <p>Detailed data analysis and reporting capabilities</p>
                </div>
                <div class="feature-item">
                    <h5>Integration</h5>
                    <p>Seamless integration with existing systems</p>
                </div>
                <div class="feature-item">
                    <h5>Customization</h5>
                    <p>Flexible customization options</p>
                </div>
                <div class="feature-item">
                    <h5>Security</h5>
                    <p>Advanced security features and protocols</p>
                </div>
            </div>
        </div>
        <div class="demo-section">
            <h4>Additional Information</h4>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>
`,be=()=>`
    <div class="iframe-container">
        <iframe 
            src="https://indicadores-ods.sedesol.gob.hn/public/dashboard/ec704f90-ad0c-46bc-9da7-7959d0b6bd50" 
            width="100%" 
            height="600px" 
            frameborder="0" 
            allowfullscreen>
        </iframe>
    </div>
`;class b{static createModal(e,t={}){const s=this.templates[e];if(!s)throw new Error(`Template ${e} not found`);return new _({content:s(),...t})}}B(b,"templates",{layerFilters:fe,mediumDemo:ye,largeDemo:ve,iframeDemo:be});const z={riskManagement:{name:"Gestión de Riesgo",layers:[{id:"fao_asi",url:"https://io.apps.fao.org/geoserver/wms/ASIS/ASI_D/v1?version=1.3.0?",layer:"ASI_D_2023-22_S2_P:ASIS:asis_asi_d_p",name:"Índice Agrícola FAO"},{id:"fao_sequia",url:"https://io.apps.fao.org/geoserver/wms/ASIS/DI_D/v1?version=1.3.0?",layer:"DI_D_2023-22_S2_P:ASIS:asis_di_d_p",name:"Índice de Sequía"},{id:"get_fao_saludvegetal",url:"https://io.apps.fao.org/geoserver/wms/ASIS/VHI_D/v1?version=1.3.0?",layer:"VHI_D_2023-22:ASIS:asis_vhi_d",name:"Salud Vegetal (VHI)"}]},fires:{name:"Incendios",layers:[{id:"firm24",url:"https://firms.modaps.eosdis.nasa.gov/mapserver/wms/fires/3db158563c718465cc2b9049e36a37f2/fires_viirs_24/?REQUEST=GetMap&WIDTH=1024&HEIGHT=512&BBOX=-180,-90,180,90",layer:"sumAL41EGE",name:"Incendios Forestales 24h"},{id:"firm48",url:"https://firms.modaps.eosdis.nasa.gov/mapserver/wms/fires/3db158563c718465cc2b9049e36a37f2/fires_modis_48/?REQUEST=GetMap&WIDTH=1024&HEIGHT=512&BBOX=-180,-90,180,90",layer:"sumAL41EGE",name:"Incendios Forestales 48h"}]},floods:{name:"Inundaciones",layers:[{id:"EGE_probRgt50",url:"https://ows.globalfloods.eu/glofas-ows/ows.py?",layer:"EGE_probRgt50",name:"Precipitación Prob. >50mm"},{id:"ForecastSkill",url:"https://ows.globalfloods.eu/glofas-ows/ows.py?",layer:"ForecastSkill",name:"Probabilidad de Inundación"}]}};class Le{constructor(){this.activeLayers=new Map,this.layerOrder=[]}addLayer(e){const t=c.getMap();if(!t){console.error("Map not initialized");return}if(this.activeLayers.has(e.id))return;const s=L.tileLayer.wms(e.url,{layers:e.layer,transparent:!0,format:"image/png",opacity:1,zIndex:1e3+this.layerOrder.length});s.on("loading",()=>{console.log(`Loading layer: ${e.name}`)}),s.on("load",()=>{console.log(`Layer loaded: ${e.name}`)}),s.addTo(t),this.activeLayers.set(e.id,{layer:s,config:e}),this.layerOrder.push(e.id),this.updateZIndexes()}removeLayer(e){const t=c.getMap(),s=this.activeLayers.get(e);s&&t&&(t.removeLayer(s.layer),this.activeLayers.delete(e),this.layerOrder=this.layerOrder.filter(i=>i!==e),this.updateZIndexes())}toggleLayer(e){if(this.activeLayers.get(e))return this.removeLayer(e),!1;{const s=this.findLayerConfig(e);if(s)return this.addLayer(s),!0}return!1}setLayerOpacity(e,t){const s=this.activeLayers.get(e);s&&s.layer.setOpacity(t)}moveLayer(e,t){const s=this.layerOrder.indexOf(e);s!==-1&&(t==="up"&&s>0?[this.layerOrder[s],this.layerOrder[s-1]]=[this.layerOrder[s-1],this.layerOrder[s]]:t==="down"&&s<this.layerOrder.length-1&&([this.layerOrder[s],this.layerOrder[s+1]]=[this.layerOrder[s+1],this.layerOrder[s]]),this.updateZIndexes())}updateZIndexes(){this.layerOrder.forEach((e,t)=>{const s=this.activeLayers.get(e);s&&s.layer.setZIndex(1e3+this.layerOrder.length-t)})}findLayerConfig(e){for(const t of Object.values(z)){const s=t.layers.find(i=>i.id===e);if(s)return s}return null}getActiveLayers(){return Array.from(this.activeLayers.values()).map(e=>({id:e.config.id,name:e.config.name,opacity:e.layer.options.opacity}))}}const I=new Le;class we{constructor(){this.activeSlider=null,this.setupGlobalClickHandler()}createSlider(e){const t=document.createElement("div");return t.className="opacity-slider",t.innerHTML=`
            <input type="range" min="0" max="100" value="100" 
                   title="Adjust layer opacity">
        `,t.querySelector("input").addEventListener("input",i=>{const a=parseInt(i.target.value)/100;I.setLayerOpacity(e,a)}),t}toggle(e,t){if(this.activeSlider&&this.activeSlider.parentElement===t.parentElement){this.activeSlider.remove(),this.activeSlider=null;return}this.activeSlider&&(this.activeSlider.remove(),this.activeSlider=null);const s=this.createSlider(e);t.parentElement.appendChild(s),requestAnimationFrame(()=>{s.classList.add("active"),this.activeSlider=s})}setupGlobalClickHandler(){document.addEventListener("click",e=>{this.activeSlider&&!e.target.closest(".opacity-slider")&&!e.target.closest(".opacity-control")&&(this.activeSlider.remove(),this.activeSlider=null)})}}class xe{constructor(){this.opacityControl=new we,this.init()}init(){const e=this.createLayerTree(),t=document.getElementById("tab1"),s=t.querySelector(".search-layers");s?s.insertAdjacentElement("afterend",e):t.appendChild(e)}createLayerTree(){const e=document.createElement("div");return e.className="layer-tree",Object.entries(C).forEach(([t,s])=>{e.appendChild(this.createCategoryGroup(s,"wfs"))}),Object.entries(z).forEach(([t,s])=>{e.appendChild(this.createCategoryGroup(s,"wms"))}),e}createCategoryGroup(e,t){const s=document.createElement("div");s.className="layer-group";const i=this.createCategoryHeader(e,t),a=this.createLayersContainer(e,t);return s.appendChild(i),s.appendChild(a),s}createCategoryHeader(e,t){const s=document.createElement("div");return s.className="layer-group-header",s.innerHTML=`
            <i class="bi bi-caret-down-fill"></i>
            <span>${e.name}</span>
            <div class="layer-toggle">
                <input type="checkbox" title="Toggle all layers in this category">
            </div>
        `,s.addEventListener("click",a=>{if(!a.target.matches("input")){s.classList.toggle("collapsed"),s.nextElementSibling.classList.toggle("collapsed");const r=s.querySelector("i");r.style.transform=s.classList.contains("collapsed")?"rotate(-90deg)":""}}),s.querySelector('input[type="checkbox"]').addEventListener("change",a=>{s.nextElementSibling.querySelectorAll('input[type="checkbox"]').forEach(l=>{l.checked!==a.target.checked&&(l.checked=a.target.checked,t==="wms"?I.toggleLayer(l.id):v.toggleLayer(l.id))})}),s}createLayersContainer(e,t){const s=document.createElement("div");return s.className="layer-items",e.layers.forEach(i=>{const a=this.createLayerItem(i,t);s.appendChild(a)}),s}createLayerItem(e,t){const s=document.createElement("div");s.className="layer-item",s.innerHTML=`
            <input type="checkbox" id="${e.id}" title="Toggle ${e.name}">
            <span>${e.name}</span>
            <div class="layer-controls">
                <button class="layer-control-btn opacity-control" title="Adjust opacity">
                    <i class="bi bi-droplet-fill"></i>
                </button>
            </div>
        `,s.querySelector("input").addEventListener("change",()=>{t==="wms"?I.toggleLayer(e.id):v.toggleLayer(e.id),this.updateCategoryCheckbox(s)});const a=s.querySelector(".opacity-control");return a.addEventListener("click",o=>{o.stopPropagation(),this.opacityControl.toggle(e.id,a)}),s}updateCategoryCheckbox(e){const t=e.closest(".layer-group"),s=t.querySelector('.layer-group-header input[type="checkbox"]'),i=Array.from(t.querySelectorAll('.layer-items input[type="checkbox"]')),a=i.filter(o=>o.checked).length;s.checked=a===i.length,s.indeterminate=a>0&&a<i.length}}const T=document.querySelector(".sidebar-left"),Ee=document.querySelector(".sidebar-toggle"),D=document.querySelectorAll(".tab-button"),Se=document.querySelectorAll(".tab-content"),Ce=document.getElementById("map");Ee.addEventListener("click",()=>{T.classList.toggle("active"),Ce.style.marginLeft=T.classList.contains("active")?"300px":"0",h.invalidateSize()});D.forEach(n=>{n.addEventListener("click",()=>{D.forEach(t=>t.classList.remove("active")),Se.forEach(t=>t.classList.remove("active")),n.classList.add("active");const e=n.getAttribute("data-tab");document.getElementById(e).classList.add("active")})});document.addEventListener("DOMContentLoaded",()=>{new xe});const Ie=()=>{const n=`
        <div class="data-actions">
            <button id="openLayerFilters" class="sidebar-action-btn">
                <i class="bi bi-funnel-fill"></i>
                <span>Filtrar Capas</span>
            </button>
            <button id="openMediumModal" class="sidebar-action-btn">
                <i class="bi bi-window"></i>
                <span>Abrir Modal Mediano</span>
            </button>
            <button id="openLargeModal" class="sidebar-action-btn">
                <i class="bi bi-window-plus"></i>
                <span>Abrir Modal Grande</span>
            </button>
            <button id="openIframeModal" class="sidebar-action-btn">
                <i class="bi bi-graph-up"></i>
                <span>Dashboard ODS</span>
            </button>
        </div>
    `,e=document.getElementById("tab2");e&&(e.innerHTML=n)};Ie();var P;(P=document.getElementById("openLayerFilters"))==null||P.addEventListener("click",()=>{b.createModal("layerFilters",{size:"md",title:"Filtro de Capas",subtitle:"Seleccione los filtros para las capas",onConfirm:()=>console.log("Aplicando filtros...")}).open()});var $;($=document.getElementById("openMediumModal"))==null||$.addEventListener("click",()=>{b.createModal("mediumDemo",{size:"md",title:"Modal Mediano",subtitle:"Ejemplo de modal de tamaño mediano",onConfirm:()=>console.log("Acción del modal mediano")}).open()});var A;(A=document.getElementById("openLargeModal"))==null||A.addEventListener("click",()=>{b.createModal("largeDemo",{size:"lg",title:"Modal Grande",subtitle:"Ejemplo de modal de tamaño grande",onConfirm:()=>console.log("Acción del modal grande")}).open()});var O;(O=document.getElementById("openIframeModal"))==null||O.addEventListener("click",()=>{b.createModal("iframeDemo",{size:"xl",title:"Dashboard ODS",subtitle:"Indicadores ODS - SEDESOL",showFooter:!1,onConfirm:()=>console.log("Acción del modal iframe")}).open()});
