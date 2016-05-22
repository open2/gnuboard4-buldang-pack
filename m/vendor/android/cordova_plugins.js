cordova.define('cordova/plugin_list', function(require, exports, module) {
    module.exports = [
        {
            "file": "plugins/cordova-plugin-app-version/www/AppVersionPlugin.js",
            "id": "cordova-plugin-app-version.AppVersionPlugin",
            "clobbers": [
                "cordova.getAppVersion"
            ]
        },
        {
            "file": "plugins/cordova-plugin-camera/www/CameraConstants.js",
            "id": "cordova-plugin-camera.Camera",
            "clobbers": [
                "Camera"
            ]
        },
        {
            "file": "plugins/cordova-plugin-camera/www/CameraPopoverOptions.js",
            "id": "cordova-plugin-camera.CameraPopoverOptions",
            "clobbers": [
                "CameraPopoverOptions"
            ]
        },
        {
            "file": "plugins/cordova-plugin-camera/www/Camera.js",
            "id": "cordova-plugin-camera.camera",
            "clobbers": [
                "navigator.camera"
            ]
        },
        {
            "file": "plugins/cordova-plugin-camera/www/CameraPopoverHandle.js",
            "id": "cordova-plugin-camera.CameraPopoverHandle",
            "clobbers": [
                "CameraPopoverHandle"
            ]
        },
        {
            "file": "plugins/cordova-plugin-device/www/device.js",
            "id": "cordova-plugin-device.device",
            "clobbers": [
                "device"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/DirectoryEntry.js",
            "id": "cordova-plugin-file.DirectoryEntry",
            "clobbers": [
                "window.DirectoryEntry"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/DirectoryReader.js",
            "id": "cordova-plugin-file.DirectoryReader",
            "clobbers": [
                "window.DirectoryReader"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/Entry.js",
            "id": "cordova-plugin-file.Entry",
            "clobbers": [
                "window.Entry"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/File.js",
            "id": "cordova-plugin-file.File",
            "clobbers": [
                "window.File"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/FileEntry.js",
            "id": "cordova-plugin-file.FileEntry",
            "clobbers": [
                "window.FileEntry"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/FileError.js",
            "id": "cordova-plugin-file.FileError",
            "clobbers": [
                "window.FileError"
            ]
        },
        // {
        //     "file": "plugins/cordova-plugin-file/www/FileReader.js",
        //     "id": "cordova-plugin-file.FileReader",
        //     "clobbers": [
        //         "window.FileReader"
        //     ]
        // },
        {
            "file": "plugins/cordova-plugin-file/www/FileSystem.js",
            "id": "cordova-plugin-file.FileSystem",
            "clobbers": [
                "window.FileSystem"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/FileUploadOptions.js",
            "id": "cordova-plugin-file.FileUploadOptions",
            "clobbers": [
                "window.FileUploadOptions"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/FileUploadResult.js",
            "id": "cordova-plugin-file.FileUploadResult",
            "clobbers": [
                "window.FileUploadResult"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/FileWriter.js",
            "id": "cordova-plugin-file.FileWriter",
            "clobbers": [
                "window.FileWriter"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/Flags.js",
            "id": "cordova-plugin-file.Flags",
            "clobbers": [
                "window.Flags"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/LocalFileSystem.js",
            "id": "cordova-plugin-file.LocalFileSystem",
            "clobbers": [
                "window.LocalFileSystem"
            ],
            "merges": [
                "window"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/Metadata.js",
            "id": "cordova-plugin-file.Metadata",
            "clobbers": [
                "window.Metadata"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/ProgressEvent.js",
            "id": "cordova-plugin-file.ProgressEvent",
            "clobbers": [
                "window.ProgressEvent"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/fileSystems.js",
            "id": "cordova-plugin-file.fileSystems"
        },
        {
            "file": "plugins/cordova-plugin-file/www/requestFileSystem.js",
            "id": "cordova-plugin-file.requestFileSystem",
            "clobbers": [
                "window.requestFileSystem"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/resolveLocalFileSystemURI.js",
            "id": "cordova-plugin-file.resolveLocalFileSystemURI",
            "merges": [
                "window"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/android/FileSystem.js",
            "id": "cordova-plugin-file.androidFileSystem",
            "merges": [
                "FileSystem"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file/www/fileSystems-roots.js",
            "id": "cordova-plugin-file.fileSystems-roots",
            "runs": true
        },
        {
            "file": "plugins/cordova-plugin-file/www/fileSystemPaths.js",
            "id": "cordova-plugin-file.fileSystemPaths",
            "merges": [
                "cordova"
            ],
            "runs": true
        },
        {
            "file": "plugins/cordova-plugin-file-transfer/www/FileTransferError.js",
            "id": "cordova-plugin-file-transfer.FileTransferError",
            "clobbers": [
                "window.FileTransferError"
            ]
        },
        {
            "file": "plugins/cordova-plugin-file-transfer/www/FileTransfer.js",
            "id": "cordova-plugin-file-transfer.FileTransfer",
            "clobbers": [
                "window.FileTransfer"
            ]
        },
        {
            "file": "plugins/cordova-plugin-inappbrowser/www/inappbrowser.js",
            "id": "cordova-plugin-inappbrowser.inappbrowser",
            "clobbers": [
                "cordova.InAppBrowser.open",
                "window.open"
            ]
        },
        {
            "file": "plugins/cordova-plugin-network-information/www/network.js",
            "id": "cordova-plugin-network-information.network",
            "clobbers": [
                "navigator.connection",
                "navigator.network.connection"
            ]
        },
        {
            "file": "plugins/cordova-plugin-network-information/www/Connection.js",
            "id": "cordova-plugin-network-information.Connection",
            "clobbers": [
                "Connection"
            ]
        },
        {
            "file": "plugins/cordova-plugin-splashscreen/www/splashscreen.js",
            "id": "cordova-plugin-splashscreen.SplashScreen",
            "clobbers": [
                "navigator.splashscreen"
            ]
        },
        {
            "file": "plugins/cordova-plugin-whitelist/whitelist.js",
            "id": "cordova-plugin-whitelist.whitelist",
            "runs": true
        },
        {
            "file": "plugins/cordova-plugin-x-toast/www/Toast.js",
            "id": "cordova-plugin-x-toast.Toast",
            "clobbers": [
                "window.plugins.toast"
            ]
        },
        {
            "file": "plugins/cordova-plugin-x-toast/test/tests.js",
            "id": "cordova-plugin-x-toast.tests"
        },
        {
            "file": "plugins/phonegap-plugin-push/www/push.js",
            "id": "phonegap-plugin-push.PushNotification",
            "clobbers": [
                "PushNotification"
            ]
        },
        {
            "file": "plugins/cordova-plugin-admobpro/www/AdMob.js",
            "id": "cordova-plugin-admobpro.AdMob",
            "clobbers": [
                "window.AdMob"
            ]
        },
        {
            "file": "plugins/cordova-plugin-badge/www/badge.js",
            "id": "cordova-plugin-badge.Badge",
            "clobbers": [
                "plugin.notification.badge",
                "cordova.plugins.notification.badge"
            ]
        }
    ];
    module.exports.metadata =
// TOP OF METADATA
    {
        "cordova-plugin-app-version": "0.1.8",
        "cordova-plugin-camera": "2.1.0",
        "cordova-plugin-device": "1.1.1",
        "cordova-plugin-file": "4.1.0",
        "cordova-plugin-file-transfer": "1.5.0",
        "cordova-plugin-inappbrowser": "1.2.0",
        "cordova-plugin-network-information": "1.2.0",
        "cordova-plugin-splashscreen": "3.1.0",
        "cordova-plugin-whitelist": "1.2.1",
        "cordova-plugin-x-toast": "2.4.0",
        "phonegap-plugin-push": "1.5.3",
        "cordova-plugin-extension": "1.5.1",
        "cordova-plugin-admobpro": "2.16.1",
        "cordova-plugin-app-event": "1.2.0",
        "cordova-plugin-badge": "0.7.2"
    };
// BOTTOM OF METADATA
});