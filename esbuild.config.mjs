import * as esbuild from 'esbuild'

const buildConfig = {
    entryPoints: [
        './packages/xm_dkfz_net_site/Resources/Private/TypeScript/app.ts',
        './packages/xm_dkfz_net_site/Resources/Private/TypeScript/terminal.ts'
    ],
    bundle: true,
    splitting: true,
    format: 'esm',
    sourcemap: true,
    outdir: 'packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/',
    logLevel: 'info',
}

if (process.argv.includes('--build')) {
    await build(buildConfig)
} else {
    await watch(buildConfig)
}

async function watch(config) {
    let ctx = await esbuild.context(config)
    await ctx.watch()
}

async function build(config) {
    config.sourcemap = false
    config.minify = true
    config.splitting = false
    config.outExtension = { '.js': '.min.js' }
    await esbuild.build(config)
}
