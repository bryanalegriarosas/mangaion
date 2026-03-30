export function formatReaders(n: number): string {
  if (n >= 1_000_000) return `${(n / 1_000_000).toFixed(1)}M lectores`
  if (n >= 1_000)     return `${(n / 1_000).toFixed(0)}K lectores`
  return `${n} lectores`
};