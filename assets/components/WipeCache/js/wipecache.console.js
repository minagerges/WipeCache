/**
 * WipeCache
 * Copyright 2014 by Mina Gerges <mina@minagerges.com>
 **/
WipeCache = function() {
    var topic = '/wipecache/';
    var register = 'mgr';
    var action = 'wipecache';
    var url = MODx.config.assets_url + 'components/WipeCache/controllers/wipecache.php';
    url = url.replace('index.php', '');
    var haspermission;

    var console = MODx.load({
       xtype: 'modx-console'
       ,register: register
       ,topic: topic
       ,clear: true
       ,show_filename: 0
       ,listeners: {
                    'shutdown': {fn:function() {
                                            if (haspermission) //prevent page reload & do not proceed if permission denied
                                                    FlushPermissions();
                    },scope:this} }
            });
    console.show(Ext.getBody());

    MODx.Ajax.request({
            url: url
            ,params: {
                    action: action
                    ,register: register
                    ,topic: topic
                    ,media_sources: true
                    ,menu: true
                    ,action_map: true
            }
            ,listeners: {
                    'success':{fn:function() {
                            haspermission = true;
                            console.fireEvent('complete');
                    },scope:this},
                    'failure':{fn:function() {  
                            console.fireEvent('complete');
                    },scope:this} }
    });
};

FlushSessions = function() {
        MODx.msg.confirm({
        title: _('flush_sessions')
        ,text: _('flush_sessions_confirm')
        ,url: MODx.config.connector_url
        ,params: {
            action: 'security/flush'
        }
        ,listeners: {
            'success': {fn:function() { location.href = './'; },scope:this},
            'failure': {fn:function() { window.location.reload(); },scope:this},
            'cancel':  {fn:function() { window.location.reload(); },scope:this} }
    });
}

FlushPermissions = function() {
    MODx.msg.confirm({
    title: _('flush_access')
    ,text: _('flush_access_confirm')
    ,url: MODx.config.connector_url
    ,params: {
        action: 'security/access/flush'
    }
    ,listeners: {
        'success': {fn:function() { FlushSessions(); },scope:this},
        'failure': {fn:function() { FlushSessions(); },scope:this},
        'cancel':  {fn:function() { FlushSessions(); },scope:this} }
  });
}