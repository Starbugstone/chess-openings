import { expect, test, type Page } from '@playwright/test'

async function playByCoordinates(page: Page, from: string, to: string) {
  await page.getByLabel('From square').fill(from)
  await page.getByLabel('To square').fill(to)
  await page.getByRole('button', { name: 'Play move', exact: true }).click()
}

test('board input, illegal moves, orientation, undo and restart stay in sync', async ({ page }) => {
  const errors: string[] = []
  page.on('pageerror', (error) => errors.push(error.message))
  await page.goto('/dev/board')
  await playByCoordinates(page, 'e2', 'e5')
  await expect(page.getByRole('status')).toHaveText('That move is not legal in this position.')
  await playByCoordinates(page, 'e2', 'e4')
  await expect(page.getByRole('status')).toHaveText('Played e4.')
  await expect(page.getByRole('list', { name: 'Move history' })).toHaveText('e4')
  await page.getByRole('button', { name: 'Flip board' }).click()
  await expect(page.locator('.chess-board')).toHaveClass(/orientation-black/)
  await page.getByRole('button', { name: 'Undo', exact: true }).click()
  await expect(page.getByText('No moves yet.')).toBeVisible()
  await playByCoordinates(page, 'd2', 'd4')
  await page.getByRole('button', { name: 'Restart', exact: true }).click()
  await expect(page.getByText('No moves yet.')).toBeVisible()
  expect(errors).toEqual([])
})

async function tapSquare(page: Page, mobile: boolean, file: number, rank: number) {
  // Absolute points: locator actions would scroll first, and scrolling refreshes Chessground's
  // cached bounds by itself, hiding stale-bounds defects. White orientation, a=0..h=7, 1..8.
  const bounds = await page.locator('cg-board').boundingBox()
  if (!bounds) throw new Error('Board not mounted')
  const x = bounds.x + (bounds.width * (file + 0.5)) / 8
  const y = bounds.y + (bounds.height * (8.5 - rank)) / 8
  await (mobile ? page.touchscreen.tap(x, y) : page.mouse.click(x, y))
}

test('tap-to-move validates through chess.js', async ({ page }, testInfo) => {
  const mobile = testInfo.project.name === 'mobile'
  await page.goto('/dev/board')
  const board = page.locator('cg-board')
  await board.scrollIntoViewIfNeeded()
  await tapSquare(page, mobile, 4, 2)
  await expect(board.locator('square.move-dest')).toHaveCount(2)
  await tapSquare(page, mobile, 4, 4)
  await expect(page.getByRole('status')).toHaveText('Played e4.')
})

test('taps stay on the right square after content above the board moves it', async ({
  page,
}, testInfo) => {
  const mobile = testInfo.project.name === 'mobile'
  // Tall enough that the shifted board stays in view, so nothing scrolls or resizes.
  if (!mobile) await page.setViewportSize({ width: 1280, height: 1400 })
  await page.goto('/dev/board')
  const board = page.locator('cg-board')
  await expect(board).toBeVisible()
  await tapSquare(page, mobile, 4, 2)
  await tapSquare(page, mobile, 4, 4)
  await expect(page.getByRole('status')).toHaveText('Played e4.')

  const before = await board.boundingBox()
  if (!before) throw new Error('Board not mounted')
  // One square of new content above the board, like a toolbar label wrapping onto two lines.
  const scrollY = await page.evaluate((height) => {
    const spacer = document.createElement('div')
    spacer.style.height = `${height}px`
    document.querySelector('.lab-grid')?.before(spacer)
    return window.scrollY
  }, before.height / 8)
  const after = await board.boundingBox()
  if (!after) throw new Error('Board not mounted')
  expect(after.y - before.y).toBeCloseTo(before.height / 8, 0)
  expect(after.width).toBe(before.width)
  expect(await page.evaluate(() => window.scrollY)).toBe(scrollY)

  await tapSquare(page, mobile, 4, 7)
  await expect(board.locator('square.move-dest')).toHaveCount(2)
  await tapSquare(page, mobile, 4, 5)
  await expect(page.getByRole('status')).toHaveText('Played e5.')
})

test('dragging a piece advances the same authoritative position', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name === 'mobile', 'Touch interaction is covered by the tap test.')
  await page.goto('/dev/board')
  await page.locator('cg-board').scrollIntoViewIfNeeded()
  const board = await page.locator('cg-board').boundingBox()
  if (!board) throw new Error('Board not mounted')
  await page.mouse.move(board.x + (board.width * 4.5) / 8, board.y + (board.height * 6.5) / 8)
  await page.mouse.down()
  await page.mouse.move(board.x + (board.width * 4.5) / 8, board.y + (board.height * 4.5) / 8, {
    steps: 12,
  })
  await page.mouse.up()
  await expect(page.getByRole('status')).toHaveText('Played e4.')
})

test('promotion requires a choice and handles underpromotion', async ({ page }) => {
  await page.goto('/dev/board')
  await page.getByText('Rules fixtures', { exact: true }).click()
  await page.getByRole('button', { name: 'Load promotion fixture' }).click()
  await page.getByRole('button', { name: 'Play move', exact: true }).click()
  await expect(page.getByRole('group', { name: 'Choose promotion piece' })).toBeVisible()
  await page.getByRole('button', { name: 'Knight', exact: true }).click()
  await expect(page.getByRole('status')).toHaveText('Played a8=N.')
  await expect(page.getByRole('list', { name: 'Move history' })).toHaveText('a8=N')
})

test('navigation cleans up the board and mobile layout fits the viewport', async ({ page }) => {
  await page.goto('/dev/board')
  await expect(page.locator('cg-board')).toHaveCount(1)
  await page.getByRole('link', { name: '← Home' }).click()
  await expect(page.getByRole('heading', { name: /Learn the line/ })).toBeVisible()
  await expect(page.locator('cg-board')).toHaveCount(1)
  await page.goto('/dev/board')
  await expect(page.locator('cg-board')).toHaveCount(1)
  expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth)).toBe(
    true,
  )
  await expect.poll(async () => (await page.request.get('/api/health')).status()).toBe(200)
})
