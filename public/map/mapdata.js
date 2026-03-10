var simplemaps_countrymap_mapdata={
  main_settings: {
   //General settings
    width: "500", //'700' or 'responsive'
    background_color: "#FFFFFF",
    background_transparent: "yes",
    border_color: "#000",
    
    //State defaults
    state_description: "State description",
    state_color: "#88A4BC",
    state_hover_color: "#3B729F",
    state_url: "",
    border_size: 1.5,
    all_states_inactive: "no",
    all_states_zoomable: "yes",
    
    //Location defaults
    location_description: "Location description",
    location_url: "",
    location_color: "#FF0067",
    location_opacity: 0.8,
    location_hover_opacity: 1,
    location_size: "70",
    location_type: "marker",
    location_image_source: "frog.png",
    location_border_color: "#FFFFFF",
    location_border: 2,
    location_hover_border: 2.5,
    all_locations_inactive: "no",
    all_locations_hidden: "no",
    
    //Label defaults
    label_color: "#d5ddec",
    label_hover_color: "#d5ddec",
    label_size: 22,
    label_font: "Arial",
    hide_labels: "no",
    hide_eastern_labels: "no",
   
    //Zoom settings
    zoom: "yes",
    manual_zoom: "yes",
    back_image: "no",
    initial_back: "no",
    initial_zoom: "-1",
    initial_zoom_solo: "no",
    region_opacity: 1,
    region_hover_opacity: 0.6,
    zoom_out_incrementally: "yes",
    zoom_percentage: 0.99,
    zoom_time: 0.5,
    
    //Popup settings
    popup_color: "white",
    popup_opacity: 0.9,
    popup_shadow: 1,
    popup_corners: 5,
    popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
    popup_nocss: "no",
    
    //Advanced settings
    div: "map",
    auto_load: "yes",
    url_new_tab: "no",
    images_directory: "default",
    fade_time: 0.1,
    link_text: "View Website",
    popups: "detect",
    state_image_url: "",
    state_image_position: "",
    location_image_url: ""
  },
  state_specific: {
    "0": {},
    "1": {},
    "2": {},
    JOR849: {
      name: "Aqaba",
      color: "#ff8910",
      hover_color: "#4f2601",
      description: "Aqaba Academy",
      url: "https://aqaba.orangecodingacademy.com/",
      inactive: "no"
    },
    JOR850: {
      name: "Mafraq",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR851: {
      name: "Amman",
      color: "#ff8910",
      hover_color: "#4f2601",
      description: "Amman Academy",
      url: "https://amman.orangecodingacademy.com/",
      inactive: "no"
    },
    JOR852: {
      name: "Tafilah",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR853: {
      name: "Ma`an",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR854: {
      name: "Irbid",
      color: "#ff8910",
      hover_color: "#4f2601",
      description: "Irbid Academy",
      url: "https://irbid.orangecodingacademy.com/",
      inactive: "no"
    },
    JOR855: {
      name: "Ajlun",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR856: {
      name: "Jarash",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR857: {
      name: "Balqa",
      color: "#ff8910",
      hover_color: "#4f2601",
      description: "Balqa Academy",
      url: "https://balqa.orangecodingacademy.com/",
      inactive: "no"
    },
    JOR858: {
      name: "Madaba",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR859: {
      name: "Karak",
      color: "#ccc",
      hover_color: "#4f2601",
      inactive: "YES"
    },
    JOR860: {
      name: "Zarqa",
      color: "#ff8910",
      hover_color: "#4f2601",
      description: "ZarqaAcademy",
      url: "https://zarqa.orangecodingacademy.com/",
      inactive: "no"
    }
  },
  locations: {
  "0": {
    lat: "31.55",
    lng: "35.999",
      name: "Amman",
      description: "Amman Academy",
      color: "#4f2601"
    },
    "2": {
      lat: "29.600436",
      lng: "35.346728",
      name: "Aqaba",
      color: "#4f2601"

    },
    "7": {
      lat: "32.55271215412435",
      lng: "35.84966880305858",
      name: "Irbid",
      color: "#4f2601"

    },
    "10": {
      lat: "32.039629",
      lng: "35.623889",
      name: "Balqa",
      color: "#4f2601"



    },
    "13": {
      lat: "31.723987",
      lng: "36.918792",
      name: "Zarqa",
      color: "#4f2601"


    }
  },
  labels: {
       "0": {
      name: "Aqaba",
      parent_id: "JOR849",
      x: 87.6,
      y: 1016.9,
      color: "#fff"
      
    },
    "1": {
      // name: "Mafraq",
      // parent_id: "JOR850",
      x: 769.6,
      y: 257.5,
      color: "#fff"
    },
    "2": {
      name: "Amman",
      parent_id: "JOR851",
      x: 299.9,
      y: 494.2,
      color: "#fff",
      state_description:"Hello",
      url_new_tab: 'yes',
      state_url: "https://amman.orangecodingacademy.com/codingacademy/create",
      lat: "31.95",
      lng: "35.933333",

      
    

    },
    "3": {
      // name: "Tafilah",
      // parent_id: "JOR852",
      x: 155.9,
      y: 701.8,
      color: "#fff"
      
    },
    "4": {
      // name: "Ma`an",
      // parent_id: "JOR853",
      x: 425.8,
      y: 760.2,
      color: "#fff"
    },
    "5": {
      name: "Irbid",
      parent_id: "JOR854",
      x: 193.6,
      y: 222.8,
      color: "#fff"
    },
    "6": {
      // name: "Ajlun",
      // parent_id: "JOR855",
      x: 177.7,
      y: 300.1,
      color: "#fff"
    },
    "7": {
      // name: "Jarash",
      // parent_id: "JOR856",
      x: 216.8,
      y: 321.6,
      color: "#fff"
    },
    "8": {
      name: "Balqa",
      parent_id: "JOR857",
      x: 166.6,
      y: 368.1,
      color: "#fff"
    },
    "9": {
      // name: "Madaba",
      // parent_id: "JOR858",
      x: 174.8,
      y: 500.3,
      color: "#fff"
    },
    "10": {
      // name: "Karak",
      // parent_id: "JOR859",
      x: 231.4,
      y: 627.2,
      color: "#ff"
    },
    "11": {
      name: "Zarqa",
      parent_id: "JOR860",
      x: 485.9,
      y: 454.2,
      color: "#fff"
    }
  },
  legend: {
    entries: []
  },
  regions: {},
  data: {
    data: {
      JOR849: "Aqaba",
      JOR850: "Mafraq",
      JOR851: "Amman",
      JOR852: "Tafilah",
      JOR853: "Ma`an",
      JOR854: "Irbid",
      JOR855: "Ajlun",
      JOR856: "Jarash",
      JOR857: "Balqa",
      JOR858: "Madaba",
      JOR859: "Karak",
      JOR860: "Zarqa"
    }
  }
};
