package com.brasibturger.controllers;

import java.util.List;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import com.brasibturger.dtos.MenuDTO;
import com.brasibturger.services.MenuService;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/menus")
@RequiredArgsConstructor
public class MenuController {
    
    private final MenuService menuService;
    
    @GetMapping
    public ResponseEntity<List<MenuDTO>> getAllMenus() {
        return ResponseEntity.ok(menuService.getAllMenus());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<MenuDTO>> getActiveMenus() {
        return ResponseEntity.ok(menuService.getActiveMenus());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<MenuDTO> getMenuById(@PathVariable Long id) {
        return ResponseEntity.ok(menuService.getMenuById(id));
    }
    
    @GetMapping("/search")
    public ResponseEntity<MenuDTO> searchMenuByName(@RequestParam String name) {
        return ResponseEntity.ok(menuService.searchMenuByName(name));
    }
    
    @PostMapping
    public ResponseEntity<MenuDTO> createMenu(@Valid @RequestBody MenuDTO menuDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(menuService.createMenu(menuDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<MenuDTO> updateMenu(
            @PathVariable Long id,
            @Valid @RequestBody MenuDTO menuDTO) {
        return ResponseEntity.ok(menuService.updateMenu(id, menuDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteMenu(@PathVariable Long id) {
        menuService.deleteMenu(id);
        return ResponseEntity.noContent().build();
    }
}
