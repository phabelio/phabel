# Phabel

**Write and deploy modern PHP 8 code, today.**

## Design

After hooking into composer, the transpilation process begins.  

### 1. Composer dependency graph

All dependencies of a package with a phabel configuration are processed with phabel with the same configuration, to allow transpiling towards an arbitrary version of PHP; composer takes care of choosing the best package, according to the current version of PHP.  

### 2. Phabel plugin graph

All plugins specified in the configuration of each package are added to the [phabel plugin graph](https://github.com/phabelio/phabel/blob/master/src/PluginGraph/GraphInternal.php).  
The [plugin graph](https://github.com/phabelio/phabel/blob/master/src/PluginGraph/GraphInternal.php) takes care of properly trickling configuration values and plugins down the dependency graph, as well as plugin graph optimization by merging multiple transforms (if allowed) in a single AST traversal.  

It will also detect circular references in [plugin dependencies](#32-plugin-dependencies).

### 3. Plugins

#### 3.1 AST traversal

Each [phabel plugin](https://github.com/phabelio/phabel/blob/master/src/PluginInterface.php) can specify multiple `leave*` and `enter*` methods, called when traversing down or up the dependency graph.  
These methods will be called only for nodes matching the typehint of the parameter.  

A second, optional parameter can be provided, to allow the [Traverser](https://github.com/phabelio/phabel/blob/master/src/Traverser.php) to pass a [Context](https://github.com/phabelio/phabel/blob/master/src/Context.php) object with helper methods for replacing nodes in arbitrary positions of the AST stack.  

#### 3.2 Plugin dependencies

Plugins can also specify other plugins as "dependencies" or "reverse dependencies", with the `previous`, `next`, `withPrevious`, `withNext` methods, to force some transforms to run before others.  
By using the `*with*` methods, additional plugin graph optimization is allowed by merging multiple transforms in a single AST traversal.  

Each [phabel plugin](https://github.com/phabelio/phabel/blob/master/src/PluginInterface.php) can also accept a configuration: this a simple way to reuse code, by specifying a single plugin for a class of transforms, and then requiring it from other plugins, specifying a specific configuration to trigger only certain transforms.  

#### 3.3 Plugin configuration

Configuration arrays are coupled with plugins when resolving the plugin graph.  
When possible, the plugin graph will try to merge a plugin with multiple configs into a single (or fewer) plugins using the [`mergeConfigs` method](https://github.com/phabelio/phabel/blob/master/src/PluginInterface.php) of the plugin.  

This merge method will be called automatically during plugin graph flattening, if requirement links allow it.

### 4. Transforms

[Multiple transforms are available](https://github.com/phabelio/phabel/tree/master/src/Target), covering all PHP 7 features.  
More complex and generic transforms like typehint and nested expression polyfilling can be found in the [plugin folder](https://github.com/phabelio/phabel/tree/master/src/Plugin).  
