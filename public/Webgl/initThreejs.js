var camera, scene, renderer, geometry, 
material, mesh, texture, light, controls;

init();
animate();

function init() {

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 1, 10000);
    camera.position.z = 0;
    camera.position.x = 20;
    camera.position.y = 10;
    camera.lookAt(scene.position);
    scene.add(camera);
    
    light = new THREE.DirectionalLight(0xE0E0FF, 1.5);
    light.position.set(200, 500, 200);
    scene.add(light);

    // Create the floor road
    road = new THREE.PlaneGeometry(15, 60, 1, 1);
    road.rotateX(-Math.PI / 2);
    // Load the texture and assign it to the material
    THREE.ImageUtils.crossOrigin = '';
    roadtexture = THREE.ImageUtils.loadTexture('Webgl/textures/road.jpg');
    roadtexture.wrapS = roadtexture.wrapT = THREE.RepeatWrapping;

    material = new THREE.MeshLambertMaterial({
      map: roadtexture
    });

    // Create the mesh for the floor and add it to the scene
    roadd = new THREE.Mesh(road, material);
    scene.add(roadd);

    // Create the floor road
    grass = new THREE.PlaneGeometry(700, 700, 1, 1);
    grass.rotateX(-Math.PI / 2);
    // Load the texture and assign it to the material
    THREE.ImageUtils.crossOrigin = '';
    grassTexture = THREE.ImageUtils.loadTexture('Webgl/textures/grass.jpg');
    grassTexture.wrapS = grassTexture.wrapT = THREE.RepeatWrapping;
    grassTexture.repeat.set(45, 45);

    material = new THREE.MeshLambertMaterial({
      map: grassTexture
    });

    // Create the mesh for the floor and add it to the scene
    grasss = new THREE.Mesh(grass, material);
    scene.add(grasss);

    // Create the floor border
    border = new THREE.PlaneGeometry(25, 70, 0, 0);
    border.rotateX(-Math.PI / 2);
    // Load the texture and assign it to the material
    THREE.ImageUtils.crossOrigin = '';
    borderTexture = THREE.ImageUtils.loadTexture('Webgl/textures/beton.jpg');
    borderTexture.wrapS = borderTexture.wrapT = THREE.RepeatWrapping;
    borderTexture.repeat.set(3, 5);

    material = new THREE.MeshLambertMaterial({
      map: borderTexture
    });

    // Create the mesh for the floor and add it to the scene
    borderr = new THREE.Mesh(border, material);
    scene.add(borderr);

    var logotexture = new THREE.TextureLoader().load( 'Webgl/textures/driveCook.png' );
    var logogeometry = new THREE.BoxBufferGeometry( 3, 5, 5);
    var logomaterial = new THREE.MeshBasicMaterial( { map: logotexture } );
    logo = new THREE.Mesh( logogeometry, logomaterial );
    logo.position.x = 4.5;
    logo.position.y = 5.5;
    scene.add( logo );

    renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    var loader = new THREE.ColladaLoader();
    loader.load( 'Webgl/collada/untitled.dae', function ( collada ) {

      var avatar = collada.scene;
      avatar.rotateZ(-Math.PI / 2);
      avatar.position.x = 1;
      avatar.position.z = -8;
      avatar.scale.set(2.5,2,3)

      scene.add( avatar );

    } );

}

function animate() {
    requestAnimationFrame(animate);
    render();
}

function render() {
    roadtexture.offset.y += .008;
    grassTexture.offset.y += .03;
    borderTexture.offset.y += .03;
    renderer.render(scene, camera);
}


