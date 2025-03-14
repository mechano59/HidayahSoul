/// <reference types="vite/client" />

declare module "*.vue" {
  import type { DefineComponent } from "vue"
  const component: DefineComponent<{}, {}, any>
  export default component
}

// Theme types
type ThemeType = "light" | "dark" | "system"

interface ThemeContext {
  theme: ThemeType
  setTheme: (theme: ThemeType) => void
}

