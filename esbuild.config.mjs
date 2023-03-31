import * as esbuild from 'esbuild'

const buildConfig = {
    entryPoints: [
        './packages/xm_dkfz_net_site/Resources/Private/TypeScript/app.ts',
        './packages/xm_dkfz_net_site/Resources/Private/TypeScript/terminal.ts'
    ],
    bundle: true,
    sourcemap: true,
    outdir: 'packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/',
    logLevel: 'info',
}

if (process.argv.includes('--build')) {
    await build()
} else {
    await watch()
}

async function build() {
    buildConfig.sourcemap = false
    buildConfig.minify = true
    buildConfig.outExtension = { '.js': '.min.js' }
    await esbuild.build(buildConfig)
}

async function watch() {
    let ctx = await esbuild.context(buildConfig)
    await ctx.watch()
}
